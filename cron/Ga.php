<?php
/**
 * @TODO - Please make sure to add crn job with its own schedule "php application/Scripts/cron-cli '[{"task":"Polls"}]'"
 * Created by PhpStorm.
 * User: Dima K
 * Date: 9/26/14
 * Time: 15:14
 */

namespace Cron;
use \Application\App;


class Ga extends Abstracts\Cron {

    use Traits\Lock;

    protected $logger = null;

    public function __construct()
    {
        $path = APPLICATION_PATH.'/logs/'.str_replace('\\',"/",get_class($this));
        if(!file_exists($path))
            mkdir($path, 0775, true);
        $logger = new \Library\Logger(array(
            'handlers' => array(
                new \Monolog\Handler\RotatingFileHandler($path.'/info.log', 10),
            ),
        ));

        App::$inst->log->setWriter($logger);
    }

    public function run(){
        $this->lock();
        try {
            $Ga = new \Repository\Metrics\Aggregation\Ga();
            $Ga->run();
        } catch(\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->sendMail('Ga aggregation error: ', $e->getMessage() . "<br><pre>" . $e->getTraceAsString()."</pre>Line: " . $e->getLine() . "<br>File: " . $e->getFile());
        }
        $this->unlock();

    }


    private function sendMail($subject, $body)
    {
        $this->mailer->send(
            array (
                'toAddress' => 'sergey.panarin@stuzo.com',
                'toName' => 'Sergey Panarin',
                'from' => 'Cron_NativeConfig@stuzo.com',
                'fromName' => 'Cron NativeConfig ('.APPLICATION_ENV.')',
                'subject' => $subject,
                'body' => $body
            )
        );
    }

} 