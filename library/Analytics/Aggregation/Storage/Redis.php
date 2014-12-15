<?php
namespace Library\Analytics\Aggregation\Storage;

class Redis extends Data
{

    protected $redis;

    protected function __construct($redisHost)
    {
        if (!class_exists('\Redis')) throw new \LogicException("Can't find redis class");
        $redis = new \Redis();
        $this->redis = $redis;
        $this->redis->connect($redisHost);
    }

    function get($key)
    {
        return json_decode($this->redis->get($key), true);
    }

    function set($key, $data)
    {
        $this->redis->set($key, json_encode($data));
    }

    function analyticsSave($key, $collection)
    {
        foreach ($collection as $row) {
            $date = new \DateTime();
            $date->setTimestamp($row['startTime']);
            $this->redis->hset($key, $date->format('Y-m-d'), $row['value']);
        }

    }

    function analyticsGet($key, $date)
    {
        $dateRange = $this->getDatesFromRange($date['startTime'], $date['endTime']);
        return $this->redis->hMGet($key, $dateRange);
    }

    function keyExist($key)
    {
        return $this->redis->exists($key);
    }

    function getDatesFromRange($start, $end)
    {
        $dates = array($start);
        while (end($dates) < $end) {
            $dates[] = date('Y-m-d', strtotime(end($dates) . ' +1 day'));
        }
        return $dates;
    }
}