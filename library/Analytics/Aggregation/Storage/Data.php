<?php
namespace Library\Analytics\Aggregation\Storage;

abstract class Data
{

    static $instance;


    protected function __construct()
    {

    }

    public static function getAdapter($config = null)
    {
        if (empty(self::$instance)) {
            if (!empty($config['redis']['host']))
                self::$instance = new Redis($config['redis']['host']);
            else
                self::$instance = new Mysql();
        }
        return self::$instance;
    }

    abstract function get($key);
    abstract function set($key, $data);
    abstract function analyticsSave($key, $collection);
    abstract function analyticsGet($key, $date);
}