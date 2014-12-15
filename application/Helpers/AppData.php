<?php

namespace Application\Helpers;

class AppData extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Helper_Placeholder_Container
     */
    protected $_container;

    function appData()
    {
        return $this;
    }

    function __construct()
    {
        $this->initVarContainer();
        $this->initAppData();
    }

    function __set($key, $value)
    {
        $this->_container[$key] = $value;
    }

    function __get($key)
    {
        if (isset($this->_container[$key])) {
            return $this->_container[$key];
        }

        return null;
    }

    function __isset($key)
    {
        return (isset($this->_container[$key]));
    }

    function __unset($key)
    {
        if (isset($this->_container[$key])) {
            unset($this->_container[$key]);
        }
    }

    function assign($spec, $value = null)
    {
        if (is_array($spec)) {
            $orig = $this->_container->getArrayCopy();
            $merged = array_merge($orig, $spec);
            $this->_container->exchangeArray($merged);
            return $this;
        }

        if (is_string($spec)) {
            $this->_container[$spec] = $value;
            return $this;
        }

        throw new Exception('Invalid values passed to assign()');
    }

    function reset()
    {
        $this->assign(self::defaultAppData());
        return $this;
    }

    function getVars()
    {
        return array_filter((array)$this->_container, function ($element) {
            return !empty($element);
        });
    }

    function raw()
    {
        $appData = null;
        $fbAppData = Stuzo_Facebook_SDK::getInstance()->getSignedRequest();
        if (!empty($fbAppData['app_data'])) {
            $appData = $fbAppData['app_data'];
        } else {
            $appData = Zend_Controller_Front::getInstance()->getRequest()->getParam('app_data');
        }

        return $appData;
    }

    function __toString()
    {
        $result = '';
        foreach ($this->_container as $key => &$value) {
            $value && $result .= $key . ':' . $value . ';';
        }
        return $result;
    }

    protected function initVarContainer()
    {
        if (null === $this->_container) {
            $this->_container = Zend_View_Helper_Placeholder_Registry::getRegistry()->getContainer(__CLASS__);
        }

        return $this->_container;
    }

    protected function initAppData()
    {
        $appData = null;
        $fbAppData = Stuzo_Facebook_SDK::getInstance()->getSignedRequest();
        if (!empty($fbAppData['app_data'])) {
            $appData = $fbAppData['app_data'];
        } else {
            $appData = Zend_Controller_Front::getInstance()->getRequest()->getParam('app_data');
        }

        $payload = explode(';', $appData);
        $appData = static::defaultAppData();
        foreach ($payload as $pair) {
            $params = explode(':', $pair, 2);
            $appData[$params[0]] = isset($params[1]) ? $params[1] : null;
        }

        $this->assign($appData);
    }

    protected static function defaultAppData()
    {
        return array(
            'id' => null,
            'platform' => null,
            'campaign' => null,
            'language' => null,
            'return' => null,
        );
    }
}