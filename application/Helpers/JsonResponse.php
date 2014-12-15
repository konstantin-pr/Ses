<?php
namespace Application\Helpers;
use Application\App;

class JsonResponse
{
    public static function success($data = array(), $setHeader = 'application/json; charset=utf-8', $key='data')
    {
        self::responseToJson(array(
            'success' => true,
            $key => $data
        ), $setHeader);
    }

    public static function error(\Exception $e, $setHeader = 'application/json; charset=utf-8', $message='')
    {
        $r = array(
            'success' => false,
            'error' => array(
                'code' => $e->getCode(),
            )
        );
        if ( !empty($message) ) {
                $r['error']['message'] = $message;
            } elseif($e instanceof \LogicException){
                $r['error']['message'] = $e->getMessage();
                foreach($e->data as $k => $v) {
                    $r['error'][$k] = $v;
                }
                App::$inst->log->error($e);
            } elseif($e instanceof \ImageManipulation\Exception) {
                $r['error']['message'] = $e->getMessage();
            } else {
                $r['error']['message'] = 'Something went wrong, please try again';
                App::$inst->log->error($e);
        }


        self::responseToJson($r, $setHeader);
    }

    public static function responseToJson($body, $setHeader)
    {
        $response = App::$inst->response();
        $response['Content-Type'] = $setHeader;
        $response->status(200);
        $response->body(json_encode($body));
    }
}