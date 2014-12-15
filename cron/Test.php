<?php 
//http://coderoncode.com/2014/02/17/magento-hhvm.html
namespace Cron;

use \Application\DB;
use Application\Controllers\Index;

class Test extends Abstracts\Cron
{
    public function run()
    {
        echo 'cron job here';
    }
}