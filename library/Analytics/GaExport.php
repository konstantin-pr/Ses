<?php

namespace Library\Analytics;

use Application\App;

class GaExport
{

    const cacheTime = 14400;

    private static $instance = NULL;

    private $service = NULL;
    private $metrics = NULL;
    private $config = NULL;
    private $cache = NULL;
    private $storage = NULL;
    private $aggregation = NULL;

    /**
     * @var $gaParams gaParams
     */
    private $gaParams = NULL;


    /**
     *
     */
    protected function __construct($config, $metrics)
    {
        $autoload = realpath(__DIR__.'/../../../../vendor/autoload.php');
        require_once $autoload;
        $this->config = $config;
        $this->metrics = $metrics; // FIXME: !


        $this->cache = \DB::cache();

//        $this->service = new \Zend_Gdata_Analytics(\Zend_Gdata_ClientLogin::getHttpClient(
//            $this->metrics['username'],
//            $this->metrics['password'],
//            \Zend_Gdata_Analytics::AUTH_SERVICE_NAME
//        ));
        $this->service = new \Gapi\Gapi($this->metrics['username'], $this->metrics['password']);
        $this->storage = \Library\Analytics\Aggregation\Storage\Data::getAdapter($config['config']);
    }

    /**
     * is it need be static ?? or need just __construct
     */
    public static function getInstance($config, $gaParams, $metrics, $aggregation = false)
    {
        if (empty(self::$instance)) {
            self::$instance = new gaExport($config, $metrics);
        }
        self::$instance->gaParams = $gaParams;
        self::$instance->aggregation = $aggregation;

        return self::$instance;
    }

    /**
     * visits metric for all app
     * @return array|string
     */
    public function visits()
    {

        $goal = $this->gaParams->goal;
        $cacheQueryKey = 'VISITS_' . $this->config['startTime'] . '-' . $this->config['endTime'];
        $cacheResponseKey = 'VISITS_' . $goal . '_' . $this->gaParams->platform . '_' . $this->config['startTime'] . '-' . $this->config['endTime'];

        if(!$this->aggregation && $this->storage->keyExist($this->gaParams->callParams)) {
            $data = $this->storage->analyticsGet($this->gaParams->callParams, $this->config);
            if(!empty($data) && is_array($data) && count($data)) {
                return $this->prepareAggregationResponse($data);
            }
        }

        if($this->gaParams->filters){
            $cacheQueryKey.=$this->gaParams->filters;
            $cacheResponseKey.=$this->gaParams->filters;
        }

        if ($response = $this->cache->fetch($cacheResponseKey))
            return $response;


        if (!($dataFeed = $this->cache->fetch($cacheQueryKey))) {
            $filter = array();
            $sourceFilter = $this->setFilterBySource();

            if(!empty($this->gaParams->filters))
                $filter[] = $this->gaParams->filters;
            if(!empty($sourceFilter))
                $filter[] = $sourceFilter;

            $this->service->requestReportData(
                    $this->metrics['profile'],
                    array('date','isMobile'),
                    array('sessions','users'),
                    array('date'),
                    count($filter)?implode('&&', $filter):'',
                    $this->config['startTime'],
                    $this->config['endTime'],
                    1,
                    500
                );

            $dataFeed = $this->service->getResults();
            App::$inst->log->debug(print_r($dataFeed, 1));
            $this->cache->save($cacheQueryKey, $dataFeed, gaExport::cacheTime);
        }

        $responseTmp = array();

        /**
         * @var $dataEntry \Zend_Gdata_Analytics_DataEntry
         */
        foreach ($dataFeed as $dataEntry) {
            $dimensions = $dataEntry->getDimensions();
            $metrics = $dataEntry->getMetrics();
            $startTime = (int)strtotime($dimensions['date']);
            $isMobile = (string)$dimensions['isMobile'];
            $responseTmp[$startTime][$isMobile] = array(
                'startTime' => $startTime,
                'value' => (int)$metrics[$goal],
            );

        }

        return $this->gatherResponse($responseTmp, $this->gaParams->platform, $cacheResponseKey);
    }

    /**
     * page view metric for all app
     * @return array
     */
    public function pageViews()
    {

        $cacheQueryKey = 'PAGEVIEWS_' . $this->gaParams->filters.$this->config['startTime'] . '-' . $this->config['endTime'];
        $cacheResponseKey = 'PAGEVIEWS_' .$this->gaParams->filters. $this->gaParams->unique . '_' . $this->gaParams->platform . '_' . $this->config['startTime'] . '-' . $this->config['endTime'];

        if(!$this->aggregation && $this->storage->keyExist($this->gaParams->callParams)) {
            $data = $this->storage->analyticsGet($this->gaParams->callParams, $this->config);
            if(!empty($data) && is_array($data) && count($data)) {
                return $this->prepareAggregationResponse($data);
            }
        }

        if ($response = $this->cache->fetch($cacheResponseKey))
            return $response;

        if (!($dataFeed = $this->cache->fetch($cacheQueryKey))) {

            $filter = array();
            $sourceFilter = $this->setFilterBySource();

            if(!empty($this->gaParams->filters))
                $filter[] = $this->gaParams->filters;
            if(!empty($sourceFilter))
                $filter[] = $sourceFilter;

            $this->service->requestReportData(
                $this->metrics['profile'],
                array('date','isMobile'),
                array('pageviews','uniquePageviews'),
                array('date'),
                count($filter)?implode('&&', $filter):'',
                $this->config['startTime'],
                $this->config['endTime'],
                1,
                500
            );

            $dataFeed = $this->service->getResults();

            $this->cache->save($cacheQueryKey, $dataFeed, gaExport::cacheTime);
        }

        $responseTmp = array();

        foreach ($dataFeed as $dataEntry) {
            $dimensions = $dataEntry->getDimensions();
            $metrics = $dataEntry->getMetrics();
            $startTime = (int)strtotime($dimensions['date']);
            $isMobile = (string)$dimensions['isMobile'];
            $responseTmp[$startTime][$isMobile] = array(
                'startTime' => $startTime,
                'value' => $this->gaParams->unique?(int)$metrics['uniquePageviews']:(int)$metrics['pageviews'],
            );
        }

        return $this->gatherResponse($responseTmp, $this->gaParams->platform, $cacheResponseKey);
    }

    /**
     * take page views with path
     * @return array|string
     */
    public function pageViewsPath()
    {
        $cacheQueryKey = 'PAGEVIEWSPATH_' .$this->gaParams->filters.$this->gaParams->goal. $this->config['startTime'] . '-' . $this->config['endTime'];
        $cacheResponseKey = 'PAGEVIEWSPATH_' .$this->gaParams->goal.$this->gaParams->filters.'_'. $this->gaParams->unique . '_' . $this->gaParams->platform . '_' . $this->config['startTime'] . '-' . $this->config['endTime'];

//        $this->gaParams->getFilterGoals();

//        $goals = array('/');

        if(!$this->aggregation && $this->storage->keyExist($this->gaParams->callParams)) {
            $data = $this->storage->analyticsGet($this->gaParams->callParams, $this->config);
            if(!empty($data) && is_array($data) && count($data)) {
                return $this->prepareAggregationResponse($data);
            }
        }

        if ($response = $this->cache->fetch($cacheResponseKey))
            return $response;

        if (!($dataFeed = $this->cache->fetch($cacheQueryKey))) {
            $filter = array();
            $sourceFilter = $this->setFilterBySource();

            if(!empty($this->gaParams->filters))
                $filter[] = $this->gaParams->filters;
            if(!empty($sourceFilter))
                $filter[] = $sourceFilter;
            if(!empty($this->gaParams->goal))
                $filter[] = 'pagePath=='.$this->gaParams->goal;

            $this->service->requestReportData(
                $this->metrics['profile'],
                array('date','isMobile', 'pagePath'),
                array('pageviews','uniquePageviews'),
                array('date'),
                count($filter)?implode('&&', $filter):'',
                $this->config['startTime'],
                $this->config['endTime'],
                1,
                500
            );

            $dataFeed = $this->service->getResults();
            $this->cache->save($cacheQueryKey, $dataFeed, gaExport::cacheTime);
        }

        $responseTmp = array();

        foreach ($dataFeed as $dataEntry) {
            $dimensions = $dataEntry->getDimensions();
            $metrics = $dataEntry->getMetrics();
            $startTime = (int)strtotime($dimensions['date']);
            $isMobile = (string)$dimensions['isMobile'];
            $responseTmp[$startTime][$isMobile] = array(
                'startTime' => $startTime,
                'value' => $this->gaParams->unique?(int)$metrics['uniquePageviews']:(int)$metrics['pageviews'],
            );
        }

        return $this->gatherResponse($responseTmp, $this->gaParams->platform, $cacheResponseKey);
    }

    /**
     * events metric
     * @return array|string
     */
    public function events()
    {
       // return $this->getSinglEvent();

        $category = $this->gaParams->category;
        $action = $this->gaParams->action;
        $label = $this->gaParams->label;
        $filters = $this->gaParams->filters;
        $cacheQueryKey = '_c_'.$category."_a_".$action.'_l_'.$label.'_s_'.'_f_'.$filters.'_'.$this->config['startTime'].'_e_'.$this->config['endTime'];
        $cacheResponseKey = '_c_'.$category."_a_".$action.'_l_'.$label.'_s_'.'_f_'.$filters.'_'.$this->gaParams->unique . '_' . $this->gaParams->platform.'_'.$this->config['startTime'].'_e_'.$this->config['endTime'];

        if(!$this->aggregation && $this->storage->keyExist($this->gaParams->callParams)) {
            $data = $this->storage->analyticsGet($this->gaParams->callParams, $this->config);
            if(!empty($data) && is_array($data) && count($data)) {
                return $this->prepareAggregationResponse($data);
            }
        }

        if ($response = $this->cache->fetch($cacheResponseKey))
            return $response;

        if (!($dataFeed = $this->cache->fetch($cacheQueryKey))) {
            $filter = array();
            $sourceFilter = $this->setFilterBySource();

            if (!empty($this->gaParams->filters))
                $filter[] = $this->gaParams->filters;
            if (!empty($sourceFilter))
                $filter[] = $sourceFilter;

            $metrics = array('totalEvents', 'uniqueEvents');
            $dimensions = array('date', 'isMobile');


            if ($this->gaParams->chCategory()) {
                $this->gaParams->setFilterCategory($filter);
            }
            if ($this->gaParams->chAction()) {
                $this->gaParams->setFilterAction($filter);
            }
            if ($this->gaParams->chLabel()) {
                $this->gaParams->setFilterLabel($filter);
            }
            $this->service->requestReportData(
                $this->metrics['profile'],
                $dimensions,
                $metrics,
                array('date'),
                count($filter) ? implode('&&', $filter) : '',
                $this->config['startTime'],
                $this->config['endTime'],
                1,
                500
            );

            $dataFeed = $this->service->getResults();
        }
        $responseTmp = array();

        if(!is_array($dataFeed)) print_r($dataFeed);
        foreach ($dataFeed as $dataEntry) {
            $dimensions = $dataEntry->getDimensions();
            $metrics = $dataEntry->getMetrics();
            $startTime = (int)strtotime($dimensions['date']);
            $isMobile = (string)$dimensions['isMobile'];
            $responseTmp[$startTime][$isMobile] = array(
                'startTime' => $startTime,
                'value' => $this->gaParams->unique?(int)$metrics['uniqueEvents']:(int)$metrics['totalEvents'],
            );
        }

        return $this->gatherResponse($responseTmp, $this->gaParams->platform, $cacheResponseKey);
    }


    protected function gatherResponse(&$tmp, $platform, $cacheResponseKey)
    {
        $response = array();

        foreach ($tmp as $startTime => $item) {
            switch ($platform) {
                case 'all':
                    $value = (isset($item['Yes']) ? $item['Yes']['value'] : 0) + (isset($item['No']) ? $item['No']['value'] : 0);
                    break;
                case 'mobile':
                    $value = (isset($item['Yes']) ? $item['Yes']['value'] : 0);
                    break;
                case 'desktop':
                    $value = (isset($item['No']) ? $item['No']['value'] : 0);
                    break;
            }
            $response[] = array(
                'startTime' => $startTime,
                'value' => $value
            );
        }

//        if (!empty($response))
        \DB::cache()->save($cacheResponseKey, $response, gaExport::cacheTime);

        return $response;
    }

    protected function setFilterBySource()
    {
        return APPLICATION_ENV=='production'?'source==vote.peopleschoice.com':'';
//        return 'source==vote.peopleschoice.com';
    }

    protected function prepareAggregationResponse($data)
    {
        $response = array();
        foreach ($data as $date=>$value) {
            $startTime = strtotime($date);
            $response[] = array(
                'startTime' => $startTime,
                'value' => empty($value)?0:$value,
                'aggregated' => '1'
            );
        }
        return $response;
    }
}


