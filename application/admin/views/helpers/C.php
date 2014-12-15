<?php

class Admin_View_Helper_C extends Zend_View_Helper_Abstract
{

    protected static $singleton = null;

    static function getInstance($applicationPath = APP_PATH)
    {
        if (null === self::$singleton) {
            try {
                static::$singleton = new \Zend_Translate(array(
                    'adapter' => 'array',
                    'scan' => 'filename',
                    'content' => $applicationPath . '/views/translate/',
                    'locale' => 'en_US',
                    'log' => Zend_Registry::get('log'),
                    'logUntranslated' => true
                ));
            } catch (Exception $unimportant) {
                self::$singleton = null;
                Zend_Registry::get('log')->err($unimportant->getMessage());
            }
        }

        return self::$singleton;
    }

    function c()
    {
        $t = static::getInstance();

        if (func_num_args() == 0) {
            return $t->getMessages('all');
        }

        $locales = array();
        foreach (func_get_args() as $locale) {
            if ($locale == 'current') {
                $locales[$t->getLocale()] = $t->getMessages();
            } else {
                $locales[$locale] = $t->getMessages($locale);
            }
        }

        return $locales;
    }
}