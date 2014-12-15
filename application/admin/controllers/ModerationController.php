<?php

class Admin_ModerationController extends Stuzo_Admin_App_Abstract
{

    /**
     *
     * @var \Repository\Feed
     */
    protected $_model = null;

    function init()
    {
        parent::init();

        require_once $this->getApplicationPath() . '/application/admin/DB.php';
//        require_once $this->getApplicationPath() . '/application/admin/Log.php';

        $app = Stuzo_Application_Core::getInstance($this->getApplicationPath() . '/application');
        $options = $app->getBootstrap()->getOption('resources');
        $log = new Stuzo_Application_Resource_Log($options['log']);
        Zend_Registry::set('log', $log->getLog());
        Zend_Registry::set('logger', $log->getLog());
        require_once $this->getApplicationPath() . '/vendor/autoload.php';

        $this->setNoRender();
        $this->view->config = Zend_Registry::get('config');

        $this->_model = DB::em()->getRepository('Entity\Feed');

        $this->processFormData();
        $this->preDispatch();
    }

    function preDispatch()
    {
        $this->view->page = $this->getParam('page', 1);
        $this->view->search = $this->getParam('search');
        $this->view->sort = $this->getParam('sort');
        $this->view->filter = $this->getParam('filter');
        $this->view->messages = array();
        $metadata = DB::em()->getClassMetadata($this->_model->getClassName());
        $this->view->columns = $metadata->getFieldNames();
    }

    function processFormData()
    {
        $data = $this->getParam('data', array());
        foreach($data as $param => $value){
            $this->setParam($param, $value);
        }
    }

    function getDefaultScriptsPath()
    {
        return $this->getAdminScriptsPath() . '/moderation';
    }

    function indexAction()
    {
        return $this->listAction();
    }

    function listAction()
    {
        $this->view->items = $this->_model->getPaginator($this->getRequest());
        $this->view->itemsCount = $this->view->items->getTotalItemCount();

        return $this->renderTemplate('list');
    }

    function approveAction()
    {
        $entity = $this->_model->find($this->getParam('id'));
        $this->_model->moderate($entity, $this->getParam('status'));
        $this->flashInfo('Entry ' . $entity->getModerationStatus());

        return $this->listAction();
    }

    function updateAction()
    {
        $this->_model->moderate($this->_model->find($this->getParam('id')),$this->getParam('value'));
        $this->view->noConteiner = true;
        $this->view->item = $this->_model->find($this->getParam('id'));
        return $this->renderTemplate('item.show');
    }

    function updatecancelAction()
    {
        $this->view->item = $this->_model->find($this->getParam('id'));
        return $this->renderTemplate('item.show');
    }

    function deleteAction()
    {
        if ($entity = $this->_model->find($this->getParam('id'))) {
            $this->_model->delete($entity, $softDelete = false);
            $this->flashInfo('Entity deleted');
        }

        return $this->listAction();
    }

    protected function flashInfo($info)
    {
        $this->view->messages[] = $info;
    }
}