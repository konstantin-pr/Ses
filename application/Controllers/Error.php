<?php
namespace Application\Controllers;
use Application\App;
use Slim\Slim;
use Library\SFacebook;
use Application\H;

class Error
{
    public function error()
    {
        //TODO: error here
    }
    
    public function notFound()
    {
        App::$inst->render('404.php');
    }
}
