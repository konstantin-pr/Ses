<?php

namespace Library;

class Config
{

    /**
     * 
     */
    protected function __construct(){}
    
    protected static $configs = array();
    
    /**
     * 
     * @param string $env
     */
    public static function getConfig($env = null)
    {
        is_null($env) && ($env = APPLICATION_ENV);
        if(isset(static::$configs[$env])) return static::$configs[$env];
        
        $rawConfig = static::open($env);
        $config = static::parse($rawConfig);
        static::$configs[$env] = $config;
        return $config;
    }
    
    protected static function open($env)
    {
        $iniPath = APPLICATION_PATH . '/configs/' . $env . '.ini';
        if (!file_exists($iniPath)) throw new \Exception('Config file not found');
        $iniFile = parse_ini_file($iniPath, true);
        if ($iniFile === false) throw new \Exception('Config file have wrong format');
        return $iniFile;
    }
    
    protected static function parse($rawConfig)
    {
        $resArr = array();
        foreach ($rawConfig as $key => $val) {
            $keyArr = explode('.', $key);
            $prevArr = &$resArr;
            foreach ($keyArr as $curKey) {
                if(!isset($prevArr[$curKey])){
                    $prevArr[$curKey] = array();
                }
    
                $prevArr = &$prevArr[$curKey];
            }
            $prevArr = $val;
        }
    
        static::validateDb($resArr);
        return $resArr;
    }
    
    /**
     * 
     */
    protected static function validateDb(array &$config)
    {
        if(empty($config['resources']['doctrine']['dbal']['connection']['parameters'])) {
            throw new \LogicException('resources.doctrine.dbal.connection.parameters are not configured!');
        }
        $db = &$config['resources']['doctrine']['dbal']['connection']['parameters'];
        empty($db['driver']) && ($db['driver'] = 'pdo_mysql');
        empty($db['driverOptions']) && ( $db['driverOptions'] = array( 1002 => 'SET NAMES utf8' ) );
        
        if(empty($db['host']))
            throw new \LogicException('resources.doctrine.dbal.connection.parameters.host not configured!');
        if(empty($db['dbname']))
            throw new \LogicException('resources.doctrine.dbal.connection.parameters.dbname not configured!');
        if(empty($db['user']))
            throw new \LogicException('resources.doctrine.dbal.connection.parameters.user not configured!');
        if(empty($db['password']))
            throw new \LogicException('resources.doctrine.dbal.connection.parameters.password not configured!');
    }
}
