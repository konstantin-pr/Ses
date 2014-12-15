<?php
namespace Repository;

use Repository\Base;

class Ga extends Base
{

    const QUERY = 'REPLACE INTO metrics(`created`,`name`,`countTotal`,`countUnique`)
                                          VALUES(:created,:name,:countTotal,:countUnique)';

    /**
     *
     * @var Stuzo_Metrics_Adapter_Ga
     */
    protected $adapter = null;

    public function __construct($options)
    {
        $this->adapter = new \Stuzo_Metrics_Adapter_Ga($options);
    }

    function pullEvents($date = null)
    {
        $date or $date = static::today();

        $query = $this->adapter->getDataQuery()
            ->addMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_TOTAL_EVENTS)
            ->addMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_EVENTS)
            ->addDimension(\Zend_Gdata_Analytics_DataQuery::DIMENSION_DATE)
            ->addDimension(\Zend_Gdata_Analytics_DataQuery::DIMENSION_EVENT_ACTION)
            ->addSort(\Zend_Gdata_Analytics_DataQuery::DIMENSION_DATE)
            ->setStartDate($date)
            ->setEndDate($date);

        $result = $this->adapter->getDataFeed($query);
        $found = $result->count();
        $updated = 0;

        foreach ($result as $r) {
            try {
                \DB::pdo()->executeQuery(static::QUERY, array(
                    'created' => $date,
                    'name' => @$r->getDimension(\Zend_Gdata_Analytics_DataQuery::DIMENSION_EVENT_ACTION),
                    'countTotal' => @$r->getMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_TOTAL_EVENTS)
                        ->getValue(),
                    'countUnique' => @$r->getMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_EVENTS)
                        ->getValue()
                ));
                $updated ++;
            } catch (\Exception $unimportant) {
                if ($unimportant->getCode() != 23000) {
                    \Zend_Registry::get('log')->err($unimportant->getMessage());
                }
            }
        }
        return array(
            'itemsFound' => $found,
            'itemsUpdated' => $updated
        );
    }

    function pullVisits($date = null)
    {
        $date or $date = static::today();

        $query = $this->adapter->getDataQuery()
            ->addMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)
            ->addMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)
            ->addMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)
            ->addMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)
            ->addDimension(\Zend_Gdata_Analytics_DataQuery::DIMENSION_DATE)
            ->addSort(\Zend_Gdata_Analytics_DataQuery::DIMENSION_DATE)
            ->setStartDate($date)
            ->setEndDate($date);

        $result = $this->adapter->getDataFeed($query);
        $found = $result->count();
        $updated = 0;

        foreach ($result as $r) {
            try {
                \DB::pdo()->executeQuery(static::QUERY, array(
                    'created' => $date,
                    'name' => 'visit',
                    'countTotal' => @$r->getMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_VISITS)
                        ->getValue(),
                    'countUnique' => @$r->getMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_VISITORS)
                        ->getValue()
                ));
                $updated ++;
            } catch (\Exception $unimportant) {
                if ($unimportant->getCode() != 23000) {
                    \Zend_Registry::get('log')->err($unimportant->getMessage());
                }
            }

            try {
                \DB::pdo()->executeQuery(static::QUERY, array(
                    'created' => $date,
                    'name' => 'pageview',
                    'countTotal' => @$r->getMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_PAGEVIEWS)
                        ->getValue(),
                    'countUnique' => @$r->getMetric(\Zend_Gdata_Analytics_DataQuery::METRIC_UNIQUE_PAGEVIEWS)
                        ->getValue()
                ));
                $updated ++;
            } catch (\Exception $unimportant) {
                if ($unimportant->getCode() != 23000) {
                    \Zend_Registry::get('log')->err($unimportant->getMessage());
                }
            }
        }
        return array(
            'itemsFound' => $found,
            'itemsUpdated' => $updated
        );
    }
}