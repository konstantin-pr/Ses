<?php
namespace Library\Analytics\Aggregation;

use Application\App;
use Application\DB;
use DoctrineExtensions\Query\Mysql\Date;
use Library\Analytics\GaExport;
use Library\Analytics\GaParams\GaParams;

class Ga
{

    const cacheXmlKey = 'analyticsAggregatorXml';
    const cacheXmlTime = 1200;
    const daysAggregation = 60;
    const daysPerIteration = 20;
    const requestPerIteration = 10;
    const dataAggregationStateKey = 'analyticsAggregationConfig';
    const metricsSavePrefix = 'metrics';


    private $analyticsXmlPath = '';
    private $analyticsXmlFile = 'analytics.php';
    private $ga = array();
    private $metricsConfig = null;
    private $storage = null;
    /**
     * EndDateRange - drop state of aggregation and begin from start
     * endTime - before this date make aggregation for metric param
     * lastMetric - stop aggregation on this metric (md5key)
     * xmlmd5 - md5 of xml file (compare if xml not changed)
     * @var array|null
     */
    private $state = null;

    public function __construct()
    {
        $this->analyticsXmlPath = APPLICATION_PATH . '/admin/configs';
        if (!isset(App::$inst->config['metrics']) && !isset(App::$inst->config['metrics']['options']))
            throw new \LogicException('Analitics config empty');
        $this->metricsConfig = App::$inst->config['metrics']['options'];
        $this->storage = Storage\Data::getAdapter(App::$inst->config);
        $this->state = $this->storage->get(self::dataAggregationStateKey);
        if (empty($this->state))
            $this->state = array();
    }

    public function run()
    {
        $xml = \DB::cache()->fetch(self::cacheXmlKey);
        if (empty($xml)) {
            $xml = $this->getAnalyticsXml();
            DB::cache()->save(self::cacheXmlKey, $xml, self::cacheXmlTime);
        }
        $xml = simplexml_load_string($xml);
        if (!empty($this->state['drop']))
            $this->dropState();

        $this->aggregate($xml);
        $this->analytics();

    }

    private function analytics()
    {
        if (!count($this->ga)) throw new \LogicException('No data in analytics xml for aggregation');
        $daysInterval = $this->daysInterval();
        $this->log(print_r($daysInterval, 1));
        $numberOfRequests = 0;

        foreach ($this->ga as $key => $row) {
            if ($numberOfRequests == self::requestPerIteration) {
                $this->state['lastMetric'] = $key;
                break;
            }
            if (!empty($this->state['lastMetric']) && $this->state['lastMetric'] > $key) continue;
//            $this->log(print_r($row, 1));
            $metrics = $this->metricGet($row, $daysInterval['startTime'], $daysInterval['endTime']);
            $this->storage->analyticsSave($row, $metrics);
            if (($key + 1) == count($this->ga)) {
                $this->state['endTime'] = $daysInterval['startTime'];
                unset($this->state['lastMetric']);
                if (!empty($this->state['EndDateRange']))
                    $this->dropState();
            }
            $this->log($key);
            $this->log($key." : ".$row.' : '.count($metrics));
            $numberOfRequests++;
        }
    }


    private function dropState()
    {
        $this->state = array();
    }

    private function daysInterval()
    {
        $interval = array(
            'startTime' => $this->getStartTime(),
            'endTime' => $this->getEndTime(),
        );
        return $interval;
    }

    private function getStartTime()
    {
        if (empty($this->state['endTime'])) {
            $startTime = new \DateTime();
        } else {
            $startTime = new \DateTime($this->state['endTime']);
        }
        $howDeep = new \DateTime();
        $howDeep->modify('-' . self::daysAggregation . ' day');
        $startTime->modify('-' . self::daysPerIteration . ' day');
        if ($howDeep > $startTime) {
            $startTime = $howDeep;
            $this->state['EndDateRange'] = true;
        }
        return $startTime->format('Y-m-d');

    }

    private function getEndTime()
    {
        if (empty($this->state['endTime'])) {
            $endTime = new \DateTime();
        } else {
            $endTime = new \DateTime($this->state['endTime']);
        }
        return $endTime->format('Y-m-d');
    }

    private function getAnalyticsXml()
    {
        $categoriesVoting = DB::cache()->fetch('AdminAnalyticsCategoryVotes');
        if (empty($categoriesVoting)) {
            $categoriesVoting = DB::CategoryVote()->getAllFrontend();
            if (empty($categoriesVoting)) $categoriesVoting = array();
            else DB::cache()->save('AdminAnalyticsCategoryVotes', $categoriesVoting, 120);
        }

        $nomineesVoting = DB::cache()->fetch('AdminAnalyticsNomineeVotes');
        if (empty($nomineesVoting)) {
            $nomineesVoting = DB::NomineeVote()->getAnalyticsData();
            if (empty($nomineesVoting)) $nomineesVoting = array();
            else DB::cache()->save('AdminAnalyticsNomineeVotes', $nomineesVoting, 120);
        }

        $nominationVoting = DB::cache()->fetch('AdminAnalyticsNominationVotes');
        if (empty($nominationVoting)) {
            $nominationVoting = DB::Nomination()->getAnalyticsData();

            if (empty($nominationVoting)) $nominationVoting = array();
            else DB::cache()->save('AdminAnalyticsNominationVotes', $nominationVoting, 120);
        }

        App::$inst->config('templates.path', $this->analyticsXmlPath);
        $xml = App::$inst->view->fetch($this->analyticsXmlFile, array(
            'categoriesVoting' => $categoriesVoting,
            'nomineesVoting' => $nomineesVoting,
            'nominationVoting' => $nominationVoting,
        ));
        $xmlmd5 = md5($xml);
        if (isset($this->state['xmlmd5']) && $this->state['xmlmd5'] != $xmlmd5) {
            $this->log('State was droped. Xml File changed');
            $this->dropState();
        }
        $this->state['xmlmd5'] = $xmlmd5;

//        $xml = simplexml_load_string($xml);

        return $xml;
    }

    private function aggregate(\SimpleXMLElement &$xml)
    {
        if (!isset($xml->category))
            throw new \LogicException('Wrong xml structure. Didn\'t find category element!');

        foreach ($xml->category as $category) {
            if (!isset($category->row)) continue;
            foreach ($category->row as $row) {
                if (!isset($row->blocks) && !isset($row->blocks->block) && !isset($row->blocks->block->line)) continue;
                foreach ($row->blocks->block->line as $line) {
                    $attributes = $line->attributes();
                    if (!isset($attributes['source'])) continue;
                    $source = explode('::', $attributes['source']);
                    if (!isset($source[0]) || !isset($source[1])) continue;
                    $params = json_decode($source[1]);
                    if (!is_null($params)) {
                        if ($params->method == 'custom') continue;

                        $this->ga[] = $source[1];
                    }
                }

            }

        }

    }

    /**
     * @param $callParams JSON
     * @param $startDate
     * @param $endDate
     * @return array|string
     * @throws \Exception
     */
    private function metricGet($callParams, $startDate, $endDate)
    {
        $gaParams = GaParams::factory($callParams);
        $gaExport = GaExport::getInstance(array('startTime' => $startDate, 'endTime' => $endDate, 'config' => App::$inst->config), $gaParams, $this->metricsConfig, true);
        switch ($gaParams->method) {
            case 'visits':
                return $gaExport->visits();
                break;
            case 'event':
                return $gaExport->events();
                break;
            case 'pageview':
                return $gaExport->pageViews();
                break;
            case 'pageViewPath':
                return $gaExport->pageViewsPath();
                break;
        }
    }

    private function log($str)
    {
        App::$inst->log->debug($str);
    }

    public function __destruct()
    {
        $this->storage->set(self::dataAggregationStateKey, $this->state);
    }


}