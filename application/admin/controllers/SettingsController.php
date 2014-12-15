<?php

class Admin_SettingsController extends Stuzo_Admin_App_Abstract
{
    function renderTemplate($method)
    {
        $this->getView()->setScriptPath(APPLICATION_PATH.'/../'.$this->defaultScriptsPath);
        return $this->getView()->render($this->getTemplate($method));
    }
    
    function settings()
    {
        $return = parent::settings();
        if ($this->getParam('type') == 'set') {
            //save settings
            require_once $this->getApplicationPath() . '/application/DB.php';
            require_once $this->getApplicationPath() . '/application/models/CopyExportImport.php';

            $generator = \Application\models\CopyGenerator::factory();
            $generator->generate();

        }
        return $return;
    }
}
