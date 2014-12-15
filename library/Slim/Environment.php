<?php

namespace Library\Slim;

class Environment extends \Slim\Environment
{
    /**
     * Constructor (private access)
     *
     * @param  array|null $settings If present, these are used instead of global server variables
     */
    private function __construct($settings = null)
    {
        if ($settings) {
            $this->properties = $settings;
        } else {
            $env = array();

            //The HTTP request method
            $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];

            //The IP
            $env['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];

            // Server params
            $scriptName = $_SERVER['SCRIPT_NAME']; // <-- "/foo/index.php"
            $requestUri = $_SERVER['REQUEST_URI']; // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"

            $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''; // <-- "test=abc" or ""

            // Physical path
            if (strpos($requestUri, $scriptName) !== false) {
                $physicalPath = $scriptName; // <-- Without rewriting
            } else {
                $physicalPath = str_replace('\\', '', dirname($scriptName)); // <-- With rewriting
            }
            $env['SCRIPT_NAME'] = rtrim($physicalPath, '/'); // <-- Remove trailing slashes
            $env['PATH_INFO'] = !empty($requestUri) && !empty($physicalPath) && strpos($requestUri,$physicalPath) === 0 ? substr_replace($requestUri, '', 0, strlen($physicalPath)) : $requestUri; // <-- Remove physical path
            $env['PATH_INFO'] = str_replace('?' . $queryString, '', $env['PATH_INFO']); // <-- Remove query string
            $env['PATH_INFO'] = '/' . ltrim($env['PATH_INFO'], '/'); // <-- Ensure leading slash
            // Query string (without leading "?")
            $env['QUERY_STRING'] = $queryString;

            //Name of server host that is running the script
            $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];

            //Number of server port that is running the script
            $env['SERVER_PORT'] = $_SERVER['SERVER_PORT'];

            //HTTP request headers (retains HTTP_ prefix to match $_SERVER)
            $headers = \Slim\Http\Headers::extract($_SERVER);
            foreach ($headers as $key => $value) {
                $env[$key] = $value;
            }

            //Is the application running under HTTPS or HTTP protocol?
            $env['slim.url_scheme'] = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';

            //Input stream (readable one time only; not available for multipart/form-data requests)
            $rawInput = @file_get_contents('php://input');
            if (!$rawInput) {
                $rawInput = '';
            }
            $env['slim.input'] = $rawInput;

            //Error stream
            $env['slim.errors'] = @fopen('php://stderr', 'w');
            //             print_r($env); die;
            $this->properties = $env;
        }
    }
    public static function getInstance($refresh = false)
    {
        if (is_null(static::$environment) || $refresh) {
            static::$environment = new static();
        }
    
        return static::$environment;
    }
}
