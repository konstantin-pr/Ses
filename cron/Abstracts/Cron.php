<?php 
namespace Cron\Abstracts;

use Application\App;
abstract class Cron
{
    
    /**
     * 
     * @var \stdClass
     */
    public static $task;
    
    abstract public function run();
    
    /**
     * 
     */
    protected function getCronUrls()
    {
        $urls = App::$inst->config['cron']['urls'];
        foreach ($urls as &$url) {$url = rtrim($url,'/').'/index/cron';}
        return $urls;
    }
    
    /**
     * @todo rename me
     */
    public function runViaUrl()
    {
        $urls = $this->getCronUrls();
        
        preg_match('/Cron\\\(.*)/', get_class($this),$task);
        $task = $task[1];
        $cronTask = new \Cron\Models\CronJob($task);
        foreach ($urls as $url) {
            try {
                
                $curlHandle = curl_init($url);
                curl_setopt($curlHandle, CURLOPT_URL, $url);
                curl_setopt($curlHandle, CURLOPT_POST, 1);
//                 curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
//                 'Content-type: text/xml; charset=utf-8'
//                     ));
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array('task' => serialize($cronTask)));
                curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandle, CURLOPT_HEADER, 0);
                curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false); // for savvis
                $buffer = curl_exec($curlHandle);
                App::$inst->log->info('Cron: task:'.static::$task->task. ' curl log:' . $buffer); //FIXME: 
                $responseHttpCode = (int) curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
                curl_close($curlHandle);
            } catch (\Exception $e) {
                App::$inst->log->error($e);
            }
        }
    }
}