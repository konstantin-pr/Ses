<?php

/**
 * used to admin app content
 */
class Admin_Controller extends Stuzo_Admin_App_Abstract
{
    function menu()
    {
        return array(
            'analytics' => 1,
            'settings' => 1,
            'cms' => 1,
            'moderation' => 0
        );
    }
}