<?php

use Application\models\CopyExportImport;
use Application\models\CopyGenerator;
use Application\models\ImportData;
use Application\models\ImportFile;

class Admin_CmsController extends Stuzo_Admin_App_Abstract
{

    function init()
    {
        parent::init();

        require_once $this->getApplicationPath() . '/application/admin/DB.php';
//        require_once $this->getApplicationPath() . '/application/Log.php';
        require_once $this->getApplicationPath() . '/application/models/CopyExportImport.php';

        $app = new Stuzo_Application_Core($this->getApplicationPath() . '/application');
        $options = $app->getBootstrap()->getOption('resources');
        $log = new Stuzo_Application_Resource_Log($options['log']);
        Zend_Registry::set('log', $log->getLog());
        Zend_Registry::set('logger', $log->getLog());

        $this->view->app_url = $this->getApplicationUrl();
        $this->view->app_path = $this->getApplicationPath();

        $user = Zend_Registry::get('user');
        $this->view->userIsAdmin = $user['role'] == 'super' ? true : false;
    }

    function getDefaultScriptsPath()
    {
        return $this->getAdminScriptsPath() . '/cms';
    }

    function indexAction()
    {
	$this->winnersAction();
    }

    /**
     * upload copy import file to application/tmp folder
     * @throws Exception
     * @return null|string
     */
    public function uploadAction()
    {
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");
        $this->setNoRender();
        $fileData = $_FILES['import'];

        $file = ImportFile::factory($this->view->app_path . '/application/tmp', $fileData);

        return $file->getName();
    }

    /**
     * compare copy before update and show compare dialog
     * @return bool
     * @throws Exception
     */
    public function compareAction()
    {
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");
        $form = $this->getParam('data');
        if (isset($form['backupId'])) {
            $file = new ImportData($form['backupId']);
            $fileName = $form['backupId'];
            $this->view->type = 'backup';
            $_SESSION['backupId'] = $form['backupId'];
        } else {
            if (!isset($form['import'])) {
                $this->view->error = 'File wasn\'t open';
                return false;
            }
            $fileName = $form['import'];
            $file = ImportFile::factory($this->view->app_path . '/application/tmp', $fileName);
            $_SESSION['importFileName'] = $file->getName();
            $this->view->type = 'file';
        }
        $copyExportImport = new CopyExportImport();
        $this->view->data = $copyExportImport->compare($file);
        $this->view->fileName = $fileName;
    }

    /**
     * import copy action
     * @throws Exception
     * @return string
     */
    public function importAction()
    {
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");

        $data = $this->getParam('data', array());

        $copyExportImport = new CopyExportImport();
        $copyExportImport->makeBackup();
        if ($data['type'] == 'backup') {
            $file = new ImportData($_SESSION['backupId']);
        } else {
            if (empty($_SESSION['importFileName'])) throw new Exception('Import file name not exist in session');
            $file = ImportFile::factory($this->view->app_path . '/application/tmp', $_SESSION['importFileName']);
        }
        $this->view->response = $copyExportImport->import($data, $file);

    }

    /**
     * generate copy file
     */
    public function generateAction()
    {
        $this->setNoRender();
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");
        $generator = CopyGenerator::factory();
        $generator->generate();

        return 'success';
//        $copyExportImport->generate();
    }

    public function showBackupsAction()
    {
        $data = \DB::CopyBackup()->findAll();
        $this->view->data = $data;
    }

    public function restoreBackupAction()
    {
        try {
            $this->setNoRender();
            $copyExportImport = new CopyExportImport();
            $file = ImportFile::factory($this->view->app_path . '/application/tmp', 'backup.json');
            $response = "<span style='color: green'>" . $copyExportImport->restoreBackup($file) . "</span>";
        } catch (Exception $e) {
            $response = "<span style='color: red'>" . $e->getMessage() . "</span>";
        }
        return $response;
    }

    /**
     * export copy action
     */
    public function exportAction()
    {
        $backup = (boolean)$this->getParam('backup', false);
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");
        $this->setNoRender();
        $trackingUrls = new CopyExportImport();
        header('Content-type: application/json; charset=utf-8');
        header('Content-Disposition: attachment;filename=export.json');
        header('Cache-Control: max-age=0');
        header('Content-Transfer-Encoding: base64');
        if ($backup) {
            $file = ImportFile::factory($this->view->app_path . '/application/tmp', 'backup.json');
            echo $file->open(true);
        } else {
            echo $trackingUrls->export();
        }
        exit(1);
    }

    /**
     *
     */
    public function generateRandoms(){
        $url = "https://api.random.org/json-rpc/1/invoke";
        $data = array (
            'id' => '7966',
            'jsonrpc' => '2.0',
            'method'  => 'generateIntegers',
            'params'  => array (
                'apiKey' => '00000000-0000-0000-0000-000000000000',
                'base'   => '10',
                'max'    => '1440',
                'min'    => '0',
                'n'      => '100',
                'replacement' => 'true'
                ));
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_POST, true);
        curl_setopt($s, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_getinfo($s,CURLINFO_HTTP_CODE);
        die(var_dump($res)); 
    }

    /**
     * show winners action
     */
    public function winnersAction()
    {
    $this->view->gifts = DB::gift()->findAll();
    //die (print_r($this->view->gifts));
	//$this->view->winners = array(
	//    1 => array (1,2,4,5)
	//);
	//return "testing output from admin/CmsController/winnerAction!!!n";
    }
}