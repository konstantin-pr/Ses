<?php

if(!defined('APPLICATION_ENV')) die;

function base64url_decode($base64url)
{
    $base64 = strtr($base64url, '-_=', '+/,');
    $plainText = base64_decode($base64);
    return ($plainText);
}

function base64url_encode($pair)
{
    return base64_encode(implode(',', $pair));
}

function print_pre($expression, $return = false)
{
    $return or (print('<pre>'));
    return print_r($expression, $return);
}