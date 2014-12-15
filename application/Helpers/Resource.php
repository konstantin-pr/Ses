<?php
namespace Application\Helpers;

use Application\App;
use Application\H;

class Resource
{
    static function resource($type = '', $modules = array())
    {
        $cacheKey = 'resources<' . $type . '>' . implode(',', $modules);
        if (App::$inst->cache->contains($cacheKey)) {
            return App::$inst->cache->fetch($cacheKey);
        }

        $minimized = (isset(App::$inst->config['minimizeAssets']) ? App::$inst->config['minimizeAssets'] : '0') == '1';
        $gruntFilePath = APPLICATION_PATH . '/Views/resources.json';
        $files = array();

        if (file_exists($gruntFilePath) and false !== ($content = file_get_contents($gruntFilePath))) {
            $content = preg_replace('/\s/', '', $content);
            $files = json_decode($content, true);
        } else {
            trigger_error('Grunt file does not exist or empty or has invalid format!', E_USER_ERROR);
        }

        $modules = !empty($modules) ? $modules : array_keys($files);

        $render = '';
        foreach ($files as $module => $resources) {
            //if module is not in list
            if (!self::matchesModule($module, $modules)) {
                continue;
            }
            foreach ($resources as $mini => $maxis) {
                if ($minimized) { // minimized
                    if (self::matchesType($type, $mini)) {
                        $render .= self::render($mini);
                    }
                } // not minimized
                else {
                    if (empty($maxis) AND self::matchesType($type, $mini)) {
                        $render .= self::render($mini);
                    }
                    if (!is_array($maxis)) {
                        if (self::matchesType($type, $maxis)) {
                            $render .= self::render($maxis);
                        }
                    } else {
                        foreach ($maxis as $maxi) {
                            $found = self::expandFiles($maxi);
                            foreach ($found as $file) {
                                if (self::matchesType($type, $file)) {
                                    $render .= self::render($file);
                                }
                            }
                        }
                    }
                }
            }
        }
        App::$inst->cache->save($cacheKey, $render, 300);
        return $render;
    }

    static protected function expandFiles($pattern)
    {
        if (preg_match('/^(\/\/)|(http)/', $pattern)) {
            return array($pattern);
        }

        $root = APPLICATION_PATH . '/../public';
        $files = glob($root . $pattern);

        array_walk($files, function (&$element) use ($root) {
            $element = str_replace($root, '', $element);
        });
        return $files;
    }

    static protected function matchesType($type, $file)
    {
        return ($type == '' or $type == self::getFileExtension($file));
    }

    static protected function matchesModule($module, $modules)
    {
        return empty($modules) or in_array($module, $modules);
    }

    static protected function render($file)
    {
        static $rendered = array();
        if (in_array($file, $rendered)) {
            return '';
        } else {
            $rendered[] = $file;
        }
        $file = Cdn::getUrl($file);
        if (self::getFileExtension($file) == 'js') {
            return H::js($file);
        } elseif (self::getFileExtension($file) == 'css') {
            return H::css($file);
        }
    }

    static protected function getFileExtension($file)
    {
        $parts = explode('.', $file);
        return preg_replace('/\?.*$/', '', strtolower(end($parts)));
    }
}