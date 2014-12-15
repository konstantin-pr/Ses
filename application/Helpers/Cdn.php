<?php

namespace Application\Helpers;

use Application\App;

class Cdn {

    protected static $configUrl = null;

    protected static $version = null;

    public static function getUrl($file)
    {
        if ((substr($file, 0, 2) != '//') && static::getConfigUrl()) {
            return static::getConfigUrl() . '/' . ltrim($file, '/') . '?v=' . static::getVersion();
        }

        return $file;
    }

    public static function getConfigUrl()
    {
        if (is_null(static::$configUrl)) {
            $config = App::$inst->config;
            static::$configUrl = !empty($config['app']['cdn']['url']) ? rtrim($config['app']['cdn']['url'], '/') : '';
        }

        return static::$configUrl;
    }

    public static function getVersion()
    {
        if (is_null(static::$version)) {
            $config = App::$inst->config;
            if (!isset($config['app']['cdn']['version'])) {
                // .git exists
                $index = dirname(APPLICATION_PATH) . DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR . 'index';
                if (file_exists($index)) {
                    static::setVersion(filemtime($index));
                } else {
                    // .git doesn't exist (deployment using by capistrano)
                    $index = dirname(APPLICATION_PATH) . DIRECTORY_SEPARATOR . 'REVISION';
                    static::setVersion(substr(@file_get_contents($index), 0, 10));
                }
            } else {
                static::setVersion($config['app']['cdn']['version']);
            }
        }

        return static::$version;
    }

    public static function setVersion($version)
    {
        static::$version = $version;
    }
}
