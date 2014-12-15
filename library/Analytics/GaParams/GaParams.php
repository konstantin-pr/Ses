<?php
namespace Library\Analytics\GaParams;

class GaParams // FIXME abstract
{

    public $unique = false; // fixme in events !!!
    public $platform = 'all';
    public $method = NULL;
    public $callParams = NULL;

    /**
     * @type string
     */
    public $filters = NULL;

    private $availablePlatforms = array('all','mobile','desktop');


    protected static $parcesXmlParams = null;

    /**
     * @param $params JSON string
     * @return Events|GaParams|PageViewsPath|Visits
     * @throws \Exception
     */
    public static function factory($params)
    {
        $paramsJson = $params;
        $params = json_decode($params);
        if (empty($params->method)) {
            throw new \Exception('Parameter "method" not initialized in xml in json.'); // todo return string
        }
        switch ($params->method) {
            case 'event':
                $gaParams = new Events($params);
                break;
            case 'visits':
                $gaParams = new Visits($params);
                break;
            case 'pageViewPath':
                $gaParams = new PageViewsPath($params);
                break;
            default:
                $gaParams = new GaParams($params);
                break;
        }
        $gaParams->callParams = $paramsJson;
        return $gaParams;
    }

    protected function __construct($callParams)
    {

        if (empty($callParams->method)) {
            throw new \Exception('Parameter "method" not initialized in xml in json.');
        }


        $this->method = $callParams->method;
        $this->unique = !empty($callParams->unique) ? $this->setUnique($callParams->unique) : false;

        $this->platform = !empty($callParams->platform) ? $callParams->platform : 'all';
        $this->platformValidate($this->platform);

        $this->goal = !empty($callParams->goal) ? $callParams->goal : '';

        $this->filters = !empty($callParams->filters) ? $callParams->filters : NULL;
    }

    private function platformValidate($value)
    {
        if(!in_array($value, $this->availablePlatforms))
            throw new \Exception('Parameter "platform" don\'t have proper values. It can be: '.explode(',',$this->availablePlatforms));
        return true;
    }

    private function setUnique($unique)
    {
        return ($unique==="true" OR $unique===true);
    }

}

