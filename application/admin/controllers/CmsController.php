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
     * show winners action
     */
    public function winnersAction()
    {
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");
        switch($this->getParam('act')) {
            case 'generate':
                try {
                    $gifts = DB::gift()->findAll();
                    if (is_array($gifts)  && count($gifts) > 0){
                        throw new Exception("Random dates are already generated.");
                    }
                    //die(print_r($gifts));    
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
                    curl_setopt($s, CURLOPT_RETURNTRANSFER, true); 
                    curl_setopt($s, CURLOPT_POSTFIELDS, json_encode($data));
                    $res = curl_exec($s);
                    curl_close($s); 
                    
                    if ($res){
                        $res = json_decode($res, true);
                    }
                    else {
                        throw new Exception("Random data was not receive from random.org, please try again later.");
                    }

                    $game_start_date = isset($this->getParam('data')['gameStartDate']) ? new \DateTime($this->getParam('data')['gameStartDate']) : null;
                    if (!$game_start_date){
                        throw new Exception("Game starting dateshould be provided");        
                    }

                    
                    $cur_date = new \DateTime();
                    if ($game_start_date < $cur_date){
                        throw new Exception("Game starting date cannot be in the past.");
                    }

                    if (isset($res['result']['random']['data'])){
                        $i=0;
                        foreach ($res['result']['random']['data'] as $rnd) {
                            $win_date = clone $game_start_date;
                            date_modify($win_date, '+' . intval($i, 10) . ' day');                    
                            date_modify($win_date, '+' . $rnd . ' minute');
                            $gift = new \Entity\Gift($win_date);
                            DB::gift()->update($gift);
                            $i = $i + 0.5;
                        }
                    }
                    else{
                        throw new Exception("Random data was not receive from random.org, please try again later.");
                    }
                    return true;
                }
                catch (\Exception $e){
                    $this->view->error = $e->getMessage();
                }
                    
                break;
        }
        $this->view->gifts = DB::gift()->findAll();
    }

   /**
     * exports users' data
     */
    public function exportusersAction(){
        if (!$this->view->userIsAdmin) throw new Exception("User must be an admin");
        //die($this->getParam('act'));
        //die("sdsdsd");
        switch($this->getParam('act')) {
            case 'export':
                //die("EXPORT!!!");
                try {
                    $users = DB::user()->findAll();
                    $csvPath = '/tmp/oralb_' . md5(uniqid() . microtime(TRUE) . mt_rand()) . '.csv';

                    $csvh = fopen($csvPath, 'w');
                    chmod ($csvPath, 400);
                    $d = ',';
                    $e = '"';

                    $csv_header = array(
                        'firstName', 
                        'lastName', 
                        'email', 
                        'birthDate', 
                        'tocAccepted', 
                        'rulesAccepted',
                        'receiveEmails',
                        'isWinner',
                        'dateWin',
                        'gift' 
                        );
                    fputcsv($csvh, $csv_header, $d, $e);                    

                    foreach ($users as $user){
                        $data = array(
                            $user['first_name'], 
                            $user['last_name'], 
                            $user['email'], 
                            date_format($user['birth_date'], 'Y-m-d H:i:s'), 
                            ($user['toc_accepted'] != NULL)   ? 'true' : 'false', 
                            ($user['rules_accepted'] != NULL) ? 'true' : 'false',
                            ($user['receive_emails'] != NULL) ? 'true' : 'false',
                            ($user['is_winner'] != NULL) ? 'true' : 'false',
                            ($user['date_win'] != NULL) ? date_format($user['date_win'], 'Y-m-d H:i:s') : 'NULL',
                            ($user['gift'] != NULL) ? $user['gift'] : 'NULL' 
                            );
                        fputcsv($csvh, $data, $d, $e);
                    }
                    fclose($csvh);
                    
                    $csv_file = file_get_contents($csvPath);
                    header("Content-type: text/csv; charset=utf-8");
                    //header("Content-type: application/download ");
                    header("Content-Disposition: attachment; filename=users.csv");
                    //header("Content-Transfer-Encoding: binary");
                    header("Content-Length: " . mb_strlen($csv_file));
                    header("Pragma: no-cache");
                    header("Expires: 0");

//        header('Content-type: application/json; charset=utf-8');
//        header('Content-Disposition: attachment;filename=export.json');
//        header('Cache-Control: max-age=0');
//        header('Content-Transfer-Encoding: base64');


                    //$csv_file = file_get_contents($csvPath);
                    die($csv_file);
                    //$outstream = fopen("php://output", "w");       
                    //while ($res = fgets($csvh)){     
                    //    fputcsv($outstream, $res);
                    //}
                    //fclose($outstream);

                    //fclose($csvh);
                    //unlink($csvPath);
                }
                catch (\Exception $e) {                    
                    $this->view->error = $e->getMessage();
                }
            break;
        }
    }
}