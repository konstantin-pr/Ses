<?php

namespace Application;


use Library\SFacebook;
use Repository\Config;
use Slim\Slim;
use Application\Helpers\Device;


class Bootstrap
{

    protected static $definedVariables = false;


    /**
     * @internal param \Slim\Slim $app
     * @return \Slim\Slim
     */
    static public function init()
    {
        static::defineVariables();

        App::$inst =static::initSlim();

        static::defineProtocol();

        static::initConfig();
        static::initHttpAuth();
        static::initLogger();
        static::initCache();
        static::initDoctrine();
        static::initAppVars();
        static::initRouting();

        //FIXME: uncomnt this line and fix in doctrine-cli
        if (!defined('SERVER_SCRIPT')) {
            SFacebook::init();
        }

        return App::$inst;

    }

    static private function initDoctrine()
    {
        App::$inst->container->singleton('DoctrineConfig', function () {
            $paths = array(APPLICATION_PATH . "/models/Entity");
            $isDevMode = false;
            $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
            if(isset(App::$inst->config['resources']['doctrine']['proxiesPath'])) {
                $config->setProxyDir(App::$inst->config['resources']['doctrine']['proxiesPath']);
            } elseif(IS_SAVVIS) {
                $config->setProxyDir(sprintf(
                    '/tmp/%s/proxies',
                    App::$inst->config['resources']['doctrine']['dbal']['connection']['parameters']['dbname']
                ));
            } else {
                $config->setProxyDir(APPLICATION_PATH . '/tmp/proxies');
            }
            $config->setProxyNamespace('Application\tmp\proxies');
            $config->setAutoGenerateProxyClasses(true);
            return $config;
        });

        App::$inst->container->singleton('em', function () {
            $config = App::$inst->DoctrineConfig;
            $entityManager = \Doctrine\ORM\EntityManager::create(App::$inst->config['resources']['doctrine']['dbal']['connection']['parameters'], $config);
            return $entityManager;
        });

        App::$inst->cache = Bootstrap::initDoctrineCache(App::$inst->DoctrineConfig); //init doctrine cache
    }

    static private function initHttpAuth()
    {
        if (class_exists('\Slim\Extras\Middleware\HttpBasicAuth') && !empty(App::$inst->config['resources']['authbasic']['login']) && !empty(App::$inst->config['resources']['authbasic']['password'])) {
            App::$inst->add(new \Slim\Extras\Middleware\HttpBasicAuth(App::$inst->config['resources']['authbasic']['login'], App::$inst->config['resources']['authbasic']['password']));
        }
    }

    static private function initConfig()
    {
        App::$inst->container->singleton('config', function () {
            return \Library\Config::getConfig();
        });
    }

    static private function initLogger()
    {
        $app = APP::$inst;
        if(isset($app->config['log']['handler'],$app->config['log']['handlerPamarms']) && is_array($app->config['log']['handlerPamarms'])) {
            $handlerPamarms = $app->config['log']['handlerPamarms'];
            foreach ($handlerPamarms as $key => $value) {
                if(preg_match('/^\d+$/',$value)) {
                    $handlerPamarms[$key] = (int)$value;
                }
            }

            $handlerClassName = '\Monolog\Handler\\'.$app->config['log']['handler'];
            $handlerReflaction = new \ReflectionClass($handlerClassName);
            $handler = $handlerReflaction->newInstanceArgs($handlerPamarms);
            $logger = new \Library\Logger(array(
                'handlers' => array($handler),
            ));
        } elseif(IS_PRODUCTION) {
            $logger = new \Library\Logger(
                array(
                    'handlers' => array(
                        new \Monolog\Handler\SyslogHandler($app->config['resources']['doctrine']['dbal']['connection']['parameters']['dbname'], LOG_USER, \Monolog\Logger::ERROR)
                    )
                )
            );
        } else {
            $logger = new \Library\Logger(array(
                'handlers' => array(
                    new \Monolog\Handler\StreamHandler(APPLICATION_PATH . '/logs/info.log', 10),
                ),
            ));
        }

        App::$inst->log->setWriter($logger);
    }

    static private function initSlim()
    {
        $app = new \Slim\Slim(
            array(
                'debug' => (APPLICATION_ENV==='production'?false:true),
                'log.enable' => IS_PRODUCTION ? false : true,
                'log.path' => APPLICATION_PATH . '/logs',
                'templates.path' => APPLICATION_PATH . '/Views',
            )
        );

        // fix savvis rewrite
        $app->container->singleton('environment', function ($c) {
            return \Library\Slim\Environment::getInstance();
        });
        return $app;
    }

    static public function initDoctrineCache($config)
    {
        $cache = null;

        if(isset(App::$inst->config['resources']['doctrine']['orm']['cache']['adapter'])){

            switch(App::$inst->config['resources']['doctrine']['orm']['cache']['adapter']){
                case 'Doctrine\Common\Cache\MemcacheCache':
                    if(is_null(App::$inst->memcache)) return false;
                    $cache = new \Doctrine\Common\Cache\MemcacheCache();
                    $cache->setMemcache(App::$inst->memcache);
                break;

                case 'Doctrine\Common\Cache\RedisCache':
                    if(is_null(App::$inst->redis)) return false;
                    $cache = new \Doctrine\Common\Cache\RedisCache();
                    $cache->setRedis(App::$inst->redis);
                break;

                case 'Doctrine\Common\Cache\ApcCache':
                    $cache = new \Doctrine\Common\Cache\ApcCache();
                break;

                default:
                    $cache = new \Doctrine\Common\Cache\ArrayCache();
                break;
            }


        }else{
            $cache = new \Doctrine\Common\Cache\ArrayCache();
        }

        $config->setQueryCacheImpl($cache);
        $config->setResultCacheImpl($cache);
        $config->setMetadataCacheImpl($cache);

        return $cache;
    }

    static private function initCache()
    {
        if(!isset(App::$inst->config['resources']['doctrine']['orm']['cache']['options']['servers']['0']['host'])
            AND !isset(App::$inst->config['resources']['doctrine']['orm']['cache']['options']['servers']['0']['port'])){
            App::$inst->memcache = null;
            App::$inst->redis = null;
            return false;
        }

        // TODO: Move it to closures
        $host = App::$inst->config['resources']['doctrine']['orm']['cache']['options']['servers']['0']['host'];
        $port = App::$inst->config['resources']['doctrine']['orm']['cache']['options']['servers']['0']['port'];

        App::$inst->container->singleton('memcache', function() use($host, $port){
            $memcache = new \Memcache();
            $memcache->connect($host, $port);
            return $memcache;
        });

        App::$inst->container->singleton('redis', function() use($host, $port){
            $redis = new \Redis();
            $redis->connect($host, $port);
            return $redis;
        });

    }


    static private function defineVariables()
    {
        if (static::$definedVariables) return;
        static::$definedVariables = true;

        date_default_timezone_set('US/Eastern');
        defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('REDIRECT_APPLICATION_ENV') ? getenv('REDIRECT_APPLICATION_ENV') : (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development')));
        defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(__DIR__ . '/../application'));
        defined('ROOT_PATH') || define('ROOT_PATH', realpath(__DIR__ . '/..'));

        defined('IS_PRODUCTION') or define('IS_PRODUCTION', strstr(APPLICATION_ENV, 'production'));
        defined('IS_DEV') or define('IS_DEV', strstr(APPLICATION_ENV, 'development'));
        defined('IS_SAVVIS') or define('IS_SAVVIS', strstr(APPLICATION_ENV, 'savvis'));
        defined('IS_ADMIN') or define('IS_ADMIN', isset($_REQUEST['adminFrame']));
    }

    static private function defineProtocol()
    {
        if(function_exists('apache_request_headers')){
            $headers = apache_request_headers();
            $protocol = App::$inst->request->getScheme(); //'http';
            if (isset($headers['STUZO-USE-HTTPS']) && $headers['STUZO-USE-HTTPS'] == 1) {
                $protocol = 'https';
            }
            if (isset($headers['ssl']) && $headers['ssl'] == true) {
                $protocol = 'https';
            }
            if (isset($headers['HTTP_SSL']) && $headers['HTTP_SSL'] == 1) {
                $protocol = 'https';
            }
        } else {
            $protocol = 'http';
        }
        if((isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            $protocol = 'https';
        }
        define('PROTOCOL', $protocol . '://');
    }

    static private function initAppVars()
    {
        App::$inst->container->singleton('vars', function () {
            $config = App::$inst->config;
            $fb = $config['facebook'];
            $sr = App::$inst->fb->getSignedRequest();
            $cfg = new \ArrayObject(array(
                'fb' => new \ArrayObject(array(
                        'appId' => $fb['appId'], //
                        'appNamespace' => $fb['namespace'], //
                        'tabUrl' => App::$inst->appPageUrl, // www.facebook.com/pages/PAGE/ + pageId + ?sk=app_ + appId
                        'canvasUrl' => H::cu(), // apps.facebook.com/ + appNamespace
                        'channelUrl' => H::u('channel.php') . '/?locale=' . App::$inst->locale, //
                        'pageUrl' => App::$inst->pageUrl, // www.facebook.com/pages/PAGE/ + pageId +
                        'pageId' => $fb['pageId'], // pageId from config
                        'currentPageId' => App::$inst->currentPageId ? : 0, // (from POST request, or 0)
                        'permissions' => isset($fb['permissions']) ? $fb['permissions'] : '', // Facebook permissions from config
                        'userId' => App::$inst->fb->getSignedRequest() ? : 0, // (from POST request, or 0)
                        'isFan' => (boolean)@$sr['page']['liked'], // is userId fan of currentPageId (from POST request, or false)
                        'isAdmin' => (boolean)@$sr->sr['page']['admin'], // is userId admin of currentPageId (from POST request, or false)
                        'appData' => H::appData(), // from request, without parsing
                        'locale' => App::$inst->locale, // default = en_US
                        'debug' => isset(App::$inst->config['debugBar']) ? App::$inst->config['debugBar'] : '',
                    ), \ArrayObject::ARRAY_AS_PROPS),
                'ig' => new \ArrayObject(array(
                        'appId' => isset($config['instagram']['appId']) ? $config['instagram']['appId'] : null,
                        'channelUrl' => H::u('igChannel.html'),
                         'permissions' => isset($config['instagram']['permissions']) ? $config['instagram']['permissions'] : null,
                     ), \ArrayObject::ARRAY_AS_PROPS),
                'app' => new \ArrayObject(array(
                        'url' => H::u(App::$inst->request->getResourceUri()), // something like app.stuzo.net
                        'version' => H::version() ? : 1, // app version increase with each deploy/build, default = 1
                        'phase' => App::$inst->phase ? : 1, // default = 1
                        'environment' => APPLICATION_ENV, // staging, production, develop, local, ...
                        'isMobile' => Device::device()->isMobile,
                        'gaAccount' => @$config['metrics']['options']['code'],
                        'gaNamespace' => '',
                        'videoUrl' => H::videoURL()
                    ), \ArrayObject::ARRAY_AS_PROPS)
            ), \ArrayObject::ARRAY_AS_PROPS);

            return $cfg;
        });
    }

    static private function initRouting()
    {
        # Init your routing here
        App::$inst->map('/(:controller(/:action(/)))', function ($controller = 'index', $action = 'Index') {
            $controller = !empty($controller) ? ucfirst($controller) : 'Index';
            $action = !empty($action) ? $action : 'index';
            $controller = '\Application\Controllers\\' . $controller;
            if (!class_exists($controller)) {
                $controller = new \Application\Controllers\Error();
                $controller->notFound();
                return;
            }
            $controller = new $controller();
            if (!method_exists($controller, $action)) {
                $controller = new \Application\Controllers\Error();
                $controller->notFound();
                return;
            }
            $controller->$action();
        })->via('GET', 'POST');
    }

}
