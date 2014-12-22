<?php

namespace Application;

use Application\Helpers\Resource;
use Entity\TabApp;

class H
{

    /**
     * print debug
     */
    static public function debug()
    {
        if (!IS_PRODUCTION && isset(App::$inst->config['debugBar']) && App::$inst->config['debugBar']){
            echo Resource::resource('', array('debug'));
        }
    }

    /**
     * Get shorten url from bit.ly provider
     *
     * @param string $url
     * @param string $goal
     * @param string $action
     * @return string - url
     */
    static public function bitly($url = '', $goal = '', $action = '')
    {
        $url = $url . '&app_data=' . base64_encode(json_encode(array('goal' => $goal, 'action' => $action)));

        if (empty(App::$inst->config['bitly']) || empty($url)) {
            return $url;
        }

        $config = App::$inst->config['bitly'];

        $id = md5($url . '&v=1');
        $cache = App::$inst->em->getConfiguration()->getResultCacheImpl();
        /**
         * @var $cache \Doctrine\Common\Cache\Cache
         */
        $bitly = $cache->fetch($id);
        if (!$bitly) {
            $providerUrl = vsprintf('http://api.bitly.com/v3/shorten?login=%s&apiKey=%s&longUrl=%s&format=%s', array(
                $config['login'],
                $config['apiKey'],
                urlencode($url),
                'json'
            ));
            $response = json_decode(file_get_contents($url))->getBody();
            $bitly = $response->data->url;
            $cache->save($bitly, $id, 86400 * 300);
        }
        return (!empty($bitly)) ? $bitly : $url;
    }

    /**
     * @return string
     */
    static public function branch()
    {
        App::$inst->container->singleton('branch', function () {
            $files = glob(APPLICATION_PATH . '/../*.branch');
            if (!empty($files[0])) {
                return trim(pathinfo(basename($files[0]), PATHINFO_FILENAME));
            } else if (APPLICATION_ENV == 'development' && file_exists(APPLICATION_PATH . '/../.git/HEAD') ) {
                // for local development
                try {
                    $files = glob(APPLICATION_PATH . '/../.git/HEAD');
                    $head = file_get_contents($files[0]);
                    return trim(substr($head, strpos($head, 'heads/') + 6));
                } catch (Exception $e) {
                }
            }
            return 'unknown';
        });

        return App::$inst->branch;
    }

    /**
     * return css file link
     * @param $source
     * @param array $attrs
     * @return string
     */
    static public function css($source, $attrs = array())
    {
        if (null !== ($link = H::s3($source))) {
            $attrsString = '';
            foreach ($attrs as $k => $v) {
                $attrsString .= (' ' . $k . '="' . $v . '"');
            }
            return sprintf('<link rel="stylesheet" href="%s"%s />%s', $link, $attrsString, "\n");
        }
    }

    /**
     * get facebook image
     * @param $aid
     * @return string
     */
    static public function fa($aid)
    {
        return '//www.facebook.com/media/set/?set=' . $aid . '&type=1';
    }

    /**
     * get facebook event uri
     * @param $eid
     * @return string
     */
    static public function fe($eid)
    {
        return '//www.facebook.com/event.php?eid=' . $eid;
    }

    /**
     * get facebook image
     * @param $id
     * @param string $type
     * @return string
     */
    static public function fi($id, $type = 'square')
    {
        return '//graph.facebook.com/' . $id . '/picture?type=' . $type;
    }

    /**
     * get facebook profile uri
     * @param $id
     * @return string
     */
    static public function fp($id)
    {
        return '//www.facebook.com/profile.php?id=' . $id;
    }

    /**
     * @return string
     */
    static public function icon90()
    {
        return self::u('img/icon/90x90.v1.png');
    }

    /**
     * Insert script teg with resource url
     * @param $source
     * @return string
     */
    static public function js($source)
    {
        if (null !== ($link = H::s3($source))) {
            return sprintf('<script type="text/javascript" src="%s"></script>%s', $link, "\n");
        }
    }

    /**
     * canvas url
     * @param string $url
     * @return string
     */
    static public function cu($url = '')
    {
        if($url) {
            return PROTOCOL . 'apps.facebook.com/' . App::$inst->config['facebook']['namespace'] . '/' . $url;
        } else {
            return PROTOCOL . 'apps.facebook.com/' . App::$inst->config['facebook']['namespace'];
        }
    }

    /**
     *
     * @param array $tags
     */
    static public function og(array $tags)
    {
        $html = '';
        foreach ($tags as $tag) {
            if (empty($tag['ns'])) {
                $og = in_array($tag['property'], array('admins', 'app_id')) ? 'fb' : 'og';
            } else {
                $og = $tag['ns'];
            }
            $html .= sprintf('<meta property="%s:%s" content="%s"/>%s', $og, $tag['property'], $tag['content'], PHP_EOL);
        }

        echo $html;
    }

    /**
     * @param $path
     * @return string
     * TODO test regular expression
     */
    static public function s3($path)
    {
        $path = preg_match('@^((http|https)://|(//))@', $path) ? $path : self::setFileVersion($path);
        return $path;
    }

    /**
     * @param $name
     * @return string
     */
    static public function setFileVersion($name)
    {
        if (file_exists(APPLICATION_PATH . '/../public' . $name)) {
            return $name . '?' . filemtime(APPLICATION_PATH . '/../public' . $name);
        }elseif((APPLICATION_ENV=='staging-savvis' OR APPLICATION_ENV=='production-savvis') AND substr_count($name, 'uploads')){
            return $name . '?' . filemtime(APPLICATION_PATH . '/../..' . $name);
        }
    }

    /**
     * get twitter profile uri
     * @param $id
     * @return string
     */
    static public function tw($id)
    {
        return '//twitter.com/#!/' . $id;
    }

    /**
     * Prepare full web url to current project public directory
     * @param string $url
     * @return string - url
     */
    static public function u($url = '')
    {
        $host = App::$inst->request->getUrl();
        $startsWithSlash = $url[0] === '/';
        return $host . ($startsWithSlash ? $url : '/'.$url );
    }

    /**
     * replace <br /> to space
     * @var $text text to replace
     * @return string
     */
    static public function unbr($text)
    {
        return str_replace('<br />', ' ', $text);
    }

    /**@deprecated
     * @return mixed|string
     */
    static public function version()
    {
        $files = glob(APPLICATION_PATH . '/../*.version');
        if (!empty($files[0])) {
            return pathinfo(basename($files[0]), PATHINFO_FILENAME);
        }
        return '';
    }

    static public function vars($json = false)
    {
        return $json ? self::json_readable(json_encode(App::$inst->vars)) : App::$inst->vars;
    }

    static public function appData()
    {
        $sr = App::$inst->fb->getSignedRequest();
        if (isset($sr['app_data']))
            return $sr['app_data'];
        else
            return App::$inst->request->get('app_data');

    }

    static protected function json_readable($data)
    {
        $tc = 0; //tab count
        $r = ''; //result
        $q = false; //quotes
        $t = "\t"; //tab
        $nl = "\n"; //new line
        $l = strlen($data);

        for ($i = 0; $i < $l; $i++) {
            $c = $data[$i];
            if ($c == '"' && $data[$i - 1] != '\\') {
                $q = !$q;
            };
            if ($q) {
                $r .= $c;
                continue;
            }
            switch ($c) {
                case '{':
                case '[':
                    $r .= $c . $nl . str_repeat($t, ++$tc);
                    break;
                case '}':
                case ']':
                    $r .= $nl . str_repeat($t, --$tc) . $c;
                    break;
                case ',':
                    $r .= $c;
                    if ($data[$i + 1] != '{' && $data[$i + 1] != '[') {
                        $r .= $nl . str_repeat($t, $tc);
                    }
                    break;
                case ':':
                    $r .= $c . ' ';
                    break;
                default:
                    $r .= $c;
            };
        };
        return $r;
    }

    static function ogTags()
    {
        return array(
            array(
                'property' => 'app_id',
                'content' => App::$inst->fb->getAppId()
            ),
            array(
                'property' => 'admins',
                'content' => '685150568' // Eric
            ),
            array(
                'property' => 'locale',
                'content' => App::$inst->locale
            )
        );
    }

    static public function copyData($locale = 'en_US'){
        require_once APPLICATION_PATH.'/models/CopyExportImport.php';
        $url = \Application\models\CopyGenerator::factory()->getCopyUrl($locale);
        echo H::js($url);
    }

    static public function savvisInject()
    {
        $config = App::$inst->config;
        if(!empty($config['savvis']['key'])) {
            $key = $config['savvis']['key'];
            return <<<SAVVIS
    <!--Stop Irule from being inserted:    no_cookie_maker   -->
    <script src="//s.btstatic.com/tag.js">{ site: "{$key}" }</script>
    <noscript><iframe src="//s.thebrighttag.com/iframe?c={$key}" width="1" height="1" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe></noscript>

SAVVIS;
        }
        return '';
    }


    static public function videoUrl() 
    {
        $repository = App::$inst->em->getRepository('Entity\TabApp');
        try {
            $app_settings = $repository->findOneBy(array('name' => 'app'));
            if ($app_settings) {
                $app_config->$app_settings->getConfig();
            }
            else{
                return false;
            }
        } catch (\Exception $e){//if no config is present
            return false;
        }
        
        $app_config = json_decode($app_config, true);
        if ($app_config && array_key_exists('videoURL', $app_config) && $app_config['videoURL'] != NULL) {
            return $app_config['videoURL'];
        }
        else 
            return false;
    }

}
