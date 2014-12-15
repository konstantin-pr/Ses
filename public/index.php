<?php
use Application\App;
use Application\Controllers\Error;
error_reporting(E_ALL | E_STRICT);
require __DIR__.'/../vendor/autoload.php';

try {
    $app = \Application\Bootstrap::init();
    $app->run();
} catch (\Exception $e) {
    if (! IS_PRODUCTION) {
        die($e->getMessage());
    } else {
        syslog(LOG_ERR, $e->getMessage());
        echo 'Sorry, something went wrong. We are working on this';
        die();
    }
}
