<?php
namespace Application\models;

class CopyExportImport
{

    private $backupFile = '';

    public function __construct()
    {
        $this->backupFile = __DIR__ . '/../tmp/backup.json';
    }

    public function makeBackup()
    {
        $data = $this->__json_encode($this->getCopy());
        \DB::em()->createQuery('DELETE * FROM Entity\CopyBackup as cb WHERE cb.id NOT IN (SELECT cs.id FROM Entity\CopyBackup as cs ORDER cs.backup_time DESC LIMIT 10)');
        $backup = new \Entity\CopyBackup();
        $backup->setData($data);
        \DB::em()->persist($backup);
        \DB::em()->flush();
    }

    public function restoreBackup(ImportFile $file)
    {
        $data = $file->open();

        $connection = \DB::em()->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL('copy', true /* whether to cascade */));
        foreach ($data as $row) {
            $copyEntity = new \Entity\Copy();
            $copyEntity->setLocale($row['locale']);
            $copyEntity->setMessage($row['message']);
            $copyEntity->setMessageId($row['messageid']);
//            print_r($copyEntity);
            \DB::em()->persist($copyEntity);
        }
        \DB::em()->flush();
        return 'success';
    }

    /**
     * Export method
     */
    public function export()
    {
        $data = $this->getCopy();
        return $this->__json_encode($data);
    }

    /**
     * Emulation of JSON_UNESCAPED_UNICODE option for json_encode (available from php 5.4)
     * @param $arr
     * @return string
     */
    private function __json_encode($arr)
    {
        $str = json_encode($arr, defined('JSON_PRETTY_PRINT') ? JSON_PRETTY_PRINT : 0);

        $str = preg_replace_callback(
            '/\\\\u([0-9a-f]{4})/i',
            function ($matches) {
                $sym = mb_convert_encoding(
                    pack('H*', $matches[1]),
                    'UTF-8',
                    'UTF-16'
                );
                return $sym;
            },
            $str
        );

        return $str . PHP_EOL;
    }


    /**
     * @param $data
     * @param \Application\models\ImportData|\ImportData|\ImportFile $file
     * @return array
     */
    public function import($data, ImportData $file)
    {
        $queryUpdate = \DB::em()->createQuery('UPDATE \Entity\Copy c SET c.message = :message WHERE c.messageid=:messageid AND c.locale=:locale');
        $importContent = $file->open();
        $response = array('create' => 0, 'update' => 0, 'oldUpdate' => 0);

        foreach ($data as $action => $rows) {
            if (is_array($rows)) {
                foreach ($rows as $messageid => $copy) {
                    $importRow = $importContent[$messageid];
                    switch ($action) {
                        case 'needCreate':
                            $copyEntity = new \Entity\Copy();
                            $copyEntity->setLocale($importRow['locale']);
                            $copyEntity->setMessage($importRow['message']);
                            $copyEntity->setMessageId($importRow['messageid']);
                            \DB::em()->persist($copyEntity);
                            $response['create']++;
                            break;
                        case 'needUpdate':
                            $queryUpdate->setParameter('message', $copy);
                            $queryUpdate->setParameter('messageid', $importRow['messageid']);
                            $queryUpdate->setParameter('locale', $importRow['locale']);
                            $queryUpdate->execute();
                            $response['update']++;
                            break;
                        case 'old':
                            $queryUpdate->setParameter('message', $copy);
                            $queryUpdate->setParameter('messageid', $importRow['messageid']);
                            $queryUpdate->setParameter('locale', $importRow['locale']);
                            $queryUpdate->execute();
                            $response['oldUpdate']++;
                            break;
                    }
                }
            }
        }
        \DB::em()->flush();
        return $response;
    }

    /**
     * Compare import data with data in database
     * @param \Application\models\ImportData|\ImportData|\ImportFile $file
     * @throws \Exception
     * @internal param $tmpFolder - folder for temporary files
     * @return array
     */
    public function compare(ImportData $file)
    {
        $dataImport = $file->open();

        if (count($dataImport) == 0) throw new \Exception("Import data empty!");
        $compareResult = $this->makeCompare($dataImport);
//        print_r($compareResult);
        return $compareResult;
    }

    /**
     * @return array|mixed
     */
    private function openSettings()
    {
        $settingsPath = realpath(__DIR__ . '/../admin/configs/settings.php');
        $dataPrep = array();
        if (file_exists($settingsPath)) {
            $settingsFile = include $settingsPath;
            foreach ($settingsFile AS $row) {
                $dataPrep = array_merge($dataPrep, $row);
            }
        }
        return $dataPrep;
    }


    /**
     * @param $dataImport
     * @return array
     */
    private function makeCompare($dataImport)
    {
        $dataServer = $this->getCopy();
        $needUpdate = array();
        $needCreate = array();
        $oldOne = array();
        $settingsFile = $this->openSettings();
        $cnt = 0;
//        print_r($dataImport);
        foreach ($dataImport as $key => $row) {
            $date = new \DateTime($row['updated']['date'], new \DateTimeZone($row['updated']['timezone']));
            $row['label'] = isset($settingsFile[$row['messageid']]) ? $settingsFile[$row['messageid']]['label'] : $row['messageid'];
            $row['key'] = $this->getImportKey($row);
            if (!isset($dataServer[$key])) {
                $needUpdate[$row['messageid']]['import'] = $row;
                $needUpdate[$row['messageid']]['server'] = array();
                $needUpdate[$row['messageid']]['create'] = true;
            } else {
                $messageServer = $dataServer[$key]['message'];
                $messageImport = $row['message'];
                if ($messageImport != $messageServer AND $dataServer[$key]['updated'] < $date) {
                    $needUpdate[$row['messageid']]['import'] = $row;
                    $needUpdate[$row['messageid']]['server'] = $dataServer[$key];
                } elseif ($messageImport != $messageServer AND $dataServer[$key]['updated'] > $date) {
//                    echo $key.'<br>';
////                    echo $key.'<br>';
//                    echo "+==================";
                    $oldOne[$row['messageid']]['import'] = $row;
                    $oldOne[$row['messageid']]['server'] = $dataServer[$key];
                } else {
                    $cnt++;
                }
            }
        }

        return array('needUpdate' => $needUpdate, 'old' => $oldOne, 'countNotUpdate' => $cnt);

    }

    private function getImportKey($row)
    {
        return $row['messageid'] . '_' . $row['locale'];
    }

    private function makeByKey($data)
    {
        $newData = array();
        foreach ($data as $row) {
            unset($row['id']);
            $newData[$this->getImportKey($row)] = $row;
        }

        return $newData;
    }

    private function getCopy()
    {
        $query = \DB::em()->createQuery('SELECT c FROM \Entity\Copy c');
        return $this->makeByKey($query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));
    }
}

class ImportData
{
    protected $id = NULL;

    /**
     * @param $id - id of backup
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @param bool $returnJson
     * @internal param $id
     * @return mixed
     */
    public function open($returnJson = false)
    {
        $data = \DB::CopyBackup()->findOneBy(array('id' => $this->id));
        return $returnJson ? $data->getData() : json_decode($data->getData(), true);
    }

}


class ImportFile extends ImportData
{
    private $path = NULL;
    private $name = NULL;

    public function __construct($path, $name = '')
    {
        if (empty($path)) throw new \Exception('File path empty!');
        if (empty($name)) {
            $name = 'import_' . time() . '.json';
        }
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * @param $path
     * @param $name string - open|array - upload from tmp ($_FILE[file_name])
     * @throws Exception
     * @return ImportFile
     */
    public static function factory($path, $name)
    {
        if (is_array($name)) {
            $file = new ImportFile($path);
            $file->removeOldFiles();
            $file->upload($name);
        } else {
            if (empty($name)) throw new Exception("File name epmty!");
            $file = new ImportFile($path, $name);
            $file->check();
        }

        return $file;
    }

    /**
     * move uploaded file to /application/tmp by default
     * @param $fileData
     * @throws \Exception
     */
    public function upload($fileData)
    {
        if (!strstr($fileData['name'], '.json')) throw new \Exception('File haven\'ot json extension');
        move_uploaded_file($fileData['tmp_name'], $this->getFullPath());
        $this->check();
    }


    private function removeOldFiles()
    {
        if (is_dir($this->path)) {
            if ($dh = opendir($this->path)) {
                $now = time();
                while (($file = readdir($dh)) !== false) {
                    if (preg_match('/^import_[0-9]+\.json$/', $file)) {
                        $created = filemtime($this->path . '/' . $file);
                        if ($created === false) continue;
                        if (($now - $created) > 100 * 60) unlink($this->path . '/' . $file);
                    }
                }
                closedir($dh);
            }
        }
    }

    /**
     * if file exist
     */
    public function check()
    {
        if (!file_exists($this->getFullPath())) {
            throw new \Exception('File doesn\'t exist');
        }

        return true;
    }

    public function open($getJson = false)
    {
        $content = file_get_contents($this->getFullPath());
        if ($getJson) return $content;
        $fileData = json_decode($content, true);

        if ($fileData === Null) {
            $this->remove();
            throw new \Exception("Import data can't be parsed. Data in file has invalid json format!");
        }

        return $fileData;
    }

    public function remove()
    {
        unlink($this->getFullPath());
    }

    private function getFullPath()
    {
        return $this->path . '/' . $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPath()
    {
        return $this->path;
    }

}

//=======================================================================Copy file generate block ========================================================================


abstract class CopyGenerator
{
    protected $s3url = '//s3.amazonaws.com';
    protected $copyData = Null;
    protected $filePath = NULL;
    protected $env = NULL;
    static protected $envAvailable = array('development' => 'local', 'staging' => 'local', 'production' => 's3', 'production-savvis' => 'savvis', 'staging-savvis' => 'savvis');
    static protected $envPath = array('local' => 'uploads/copy', 's3' => 'projects/copy', 'savvis' => 'uploads/copy', 'savvist' => 'uploads/copy');

    public function __construct($env)
    {
        $this->env = $env;
        $this->copyData = $this->getCopy();
    }

    static public function factory()
    {
        $env = isset(self::$envAvailable[APPLICATION_ENV]) ? self::$envAvailable[APPLICATION_ENV] : 'local';
        $class = 'Application\models\\' . ucfirst($env) . 'CopyGenerate';

        return new $class($env);
    }

    protected function getCopy()
    {
        $db = class_exists('\Application\DB') ? \Application\DB::em() : \DB::em();
        $query = $db->createQuery('SELECT c FROM \Entity\Copy c');
        return $this->prepareArrayByLocales($query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));
    }

    protected function prepareArrayByLocales($data)
    {
        $res = array();
        foreach ($data as $row) {
            $res[$row['locale']][$row['messageid']] = $row['message'];
        }
        return $res;
    }


    public function getFileName($locale = null)
    {
        if ($locale == null)
            return 'localization.js';
        else
            return $locale . '.js';
    }

    public function getEnv()
    {
        return $this->env;
    }

    public function copyFileData($json)
    {
        ob_start();
        ?>
        !function (angular) {
        'use strict';
        (window.app || (window.app = angular.module('app', [])))
        //------------------------------------------------------ start

        .run(['$injector', function ($injector) {
        var
        $config = $injector.get('config'),
        $rootScope = $injector.get('$rootScope');

        $rootScope.locales =
        <?php echo $json; ?>


        $rootScope.translate = function(key, locale){return $rootScope.locales[locale || $config.fb.locale][key];};
        }]);

        //------------------------------------------------------ end
        }(angular);
        <?php
        return ob_get_clean();
    }

    abstract public function getCopyUrl($locale);

    abstract public function generate();

    abstract public function generateAll();

    abstract public function getPath();


    protected function json_readable_encode($in, $indent = 0, $from_array = false)
    {
        $_escape = function ($str) {
            return preg_replace("!([\b\t\n\r\f\"\\'])!", "\\\\\\1", $str);
        };

        $out = '';

        foreach ($in as $key => $value) {
            $out .= str_repeat("\t", $indent + 1);
            $out .= "\"" . $_escape((string)$key) . "\": ";

            if (is_object($value) || is_array($value)) {
                $out .= "\n";
                $out .= $this->json_readable_encode($value, $indent + 1);
            } elseif (is_bool($value)) {
                $out .= $value ? 'true' : 'false';
            } elseif (is_null($value)) {
                $out .= 'null';
            } elseif (is_string($value)) {
                $out .= "\"" . $_escape($value) . "\"";
            } else {
                $out .= $value;
            }

            $out .= ",\n";
        }

        if (!empty($out)) {
            $out = substr($out, 0, -2);
        }

        $out = str_repeat("\t", $indent) . "{\n" . $out;
        $out .= "\n" . str_repeat("\t", $indent) . "}";

        return $out;
    }

}

//==================================================================Copy generators==============================================

class S3CopyGenerate extends CopyGenerator
{

    /**
     * @var $s3 Zend_Service_Amazon_S3
     */
    private $s3 = NULL;
    private $s3config = NULL;

    public function __construct($env)
    {
        parent::__construct($env);
        $this->getS3();
    }

    public function generate()
    {
        foreach ($this->copyData as $locale => $localeData) {
            $json = $this->json_readable_encode($localeData);
            $data = $this->copyFileData('{' . $locale . ': ' . $json . '};');
            $filePath = $this->s3config['bucket'] . '/' . $this->getPath() . '/' . $this->getFileName($locale);
            $this->s3->putObject($filePath, $data,
                array(\Zend_Service_Amazon_S3::S3_ACL_HEADER =>
                    \Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ));
        }
    }

    public function generateAll()
    {
        $json = $this->json_readable_encode($this->copyData);
        $data = $this->copyFileData($json . ';');
        $filePath = $this->s3config['bucket'] . '/' . $this->getPath() . '/' . $this->getFileNameAll();
        $this->s3->putObject($filePath, $data,
            array(\Zend_Service_Amazon_S3::S3_ACL_HEADER =>
                \Zend_Service_Amazon_S3::S3_ACL_PUBLIC_READ));
    }

    private function getS3()
    {
        $config = \Zend_Registry::get('config');
        if (!isset($config['aws']['key']) || !isset($config['aws']['secret']) || !isset($config['aws']['bucket']))
            throw new \Exception("No aws configuration found!");
        $this->s3config = $config['aws'];
        $this->s3 = new \Zend_Service_Amazon_S3($config['aws']['key'], $config['aws']['secret']);
    }


    public function getCopyUrl($locale)
    {
        if ($this->env === 's3') {
            $config = \Zend_Registry::get('config');
            if (!isset($config['aws']['bucket']))
                throw new \Exception("No aws configuration found!");
            return $this->s3url . '/' . $config['aws']['bucket'] . '/' . $this->getPath() . '/' . $this->getFileName($locale);
        }
    }

    public function getPath()
    {
        $config = \Zend_Registry::get('config');
        if (!$config['facebook']['namespace']) throw new \Exception('facebook.namespace not found in config file');
        return self::$envPath[$this->env] . '/' . $config['facebook']['namespace'];
    }
}

class LocalCopyGenerate extends CopyGenerator
{
    protected $appPath = '';
    protected $filePath = '';

    public function __construct($env)
    {
        parent::__construct($env);
        $this->appPath = realpath(__DIR__ . '/../..');
        $this->filePath = $this->appPath . '/' . $this->getPath();
    }

    public function generate()
    {
        $this->foldersStructure($this->getPath());
        foreach ($this->copyData as $locale => $localeData) {
            $json = $this->json_readable_encode($localeData);
            $data = $this->copyFileData('{' . $locale . ': ' . $json . '};');
            $fileName = $this->filePath . '/' . $this->getFileName($locale);
            file_put_contents($fileName, $data);
        }
    }

    public function generateAll()
    {
        $this->foldersStructure($this->getPath());
        $json = $this->json_readable_encode($this->copyData);
        $data = $this->copyFileData($json . ';');
        $fileName = $this->filePath . '/' . $this->getFileName();
        file_put_contents($fileName, $data);
    }

    public function foldersStructure($path)
    {
        $pathArr = explode(DIRECTORY_SEPARATOR, $path);
        $curDirectory = $this->appPath;
        foreach ($pathArr AS $directory) {
            $curDirectory .= '/' . $directory;
            if (!file_exists($curDirectory)) {
                mkdir($curDirectory);
            }
        }
        if (!file_exists($this->filePath)) throw new \Exception('Copy file directory not created.');
    }

    public function getCopyUrl($locale)
    {
        return '/' . self::$envPath[$this->env] . '/' . $this->getFileName($locale);
    }

    public function getPath()
    {
        return 'public/' . self::$envPath[$this->env];
    }

}

class SavvisCopyGenerate extends LocalCopyGenerate
{
    public function __construct($env)
    {
        parent::__construct($env);
        $this->appPath = realpath(__DIR__ . '/../../..');
        $this->filePath = realpath($this->appPath . '/' . $this->getPath());
    }

    public function getPath()
    {
        return self::$envPath[$this->env];
    }
}
