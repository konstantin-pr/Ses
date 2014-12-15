<?php 
namespace Cron;

use \Application\DB;
use Application\Controllers\Index;

class TestCron extends Abstracts\Cron
{
    public function run($app)
    {
        $a = DB::tabApp()->find(1);
        print_r($a);
    }
}