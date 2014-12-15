<?php

class Admin_AnalyticsController extends Stuzo_Admin_App_Analytics
{
	function init()
	{
	    parent::init();

	    require_once $this->getApplicationPath() . '/application/admin/DB.php';
	}
}