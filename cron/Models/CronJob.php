<?php 
namespace Cron\Models;

use Application\App;
class CronJob
{
    protected static $tokenExpired = 300;

    protected $salt = 'so sweet salt, try it again';
    
    protected $time;
    protected $token;
    public $task;
    public $data;
    
    public function __construct($task)
    {
        $this->task = $task;
        $this->time = time();
        $this->token = md5($this->time.$this->salt);
    }
    
    public function __sleep()
    {
        return array('time','token','task','data');
    }

    public function __wakeup()
    {
        if ((time() - $this->time) > static::$tokenExpired) {
            throw new \Exception('Access token has expired.');
        }
        if ($this->token !== md5($this->time.$this->salt)) {
            throw new \Exception('Access token incorrect!');
        }
        $cronClass = '\Cron\\'.$this->task;
        if(!class_exists($cronClass)){
            throw new \Exception('Wrong task:'.$cronClass);
        }
        
        $cronObject = new $cronClass();
        $cronObject->run();
    }
}
