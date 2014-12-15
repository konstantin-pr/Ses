<?php
namespace Application\Controllers;
use Application\App;
use Slim\Slim;
use Library\SFacebook;
use Application\H;

class Index
{

    protected function fbRedirect()
    {
        if(($url = SFacebook::getRedirectUrl())) {
            App::$inst->render('redirect.php',array('redirectUrl'=>$url,'openGraphTags'=>H::ogTags()));
        } else {
            $this->renderLayout('layout');
        }
    }

    public function index()
    {
        $this->fbRedirect();
    }

    public function tab()
    {
        $this->renderLayout('layout');
    }

    public function mobile()
    {
        $this->renderLayout('layout');
    }


    /**
     * need for savvis-cron
     */
    public function cron()
    {
        $cronJob = App::$inst->request->post('task',null);
        try {
            $cronJob = unserialize($cronJob);
        } catch (\Exception $e){
            echo 'error';
        }
    }

    private function renderLayout($template)
    {
        if (APPLICATION_ENV !== 'development') {
            App::$inst->render("${template}.min.php");
        } else {
            App::$inst->render("${template}.php");
        }
    }
}
