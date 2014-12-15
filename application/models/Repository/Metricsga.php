<?php

namespace Repository;

use \Library\Analytics\GaParams;
use \Library\Analytics\GaExport;

/**
 * Class does not represent any entity, it is used for displaying metrics.
 * The purpose is to have all metrics-related methods in one place.
 */
class Metricsga
{

    public static function __callstatic($callParams, $params)
    {
        $callParamsJson = $callParams;
        $callParams = json_decode($callParams);
        $gaParams = GaParams\GaParams::factory($callParamsJson);
        $params[0]['config'] = $params[0]['config']->toArray();
        $gaExport = GaExport::getInstance($params[0], $gaParams, $params[0]['config']['metrics']['options']);

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
            case 'custom':
                $f = $callParams->action;
                return static::$f($callParams, $params);
                break;
        }

        $method = $gaParams->method;
        return self::$method($params[0]);
    }

    /**
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public static function getEntityManager()
    {
        return \Zend_Registry::get('doctrine');
    }
}

