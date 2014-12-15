<?php
namespace Application\models;
use Application\App;
/**
 * @see https://crs-api.liveworld.com/
 * @see https://crs-api.liveworld.com/LiveWorld_Content_Review_System-API_Reference.pdf
 */
class LwAdapter
{
    const STATUS_APPROVED = 0x10;
    const STATUS_REJECTED = 0x20;
    
    /**
     * auto-approve
     * @var Integer
     */
    const LW_MANUAL_STATUS_APPROVED = 208;
    /**
     * auto-reject
     * @var Integer
     */
    const LW_MANUAL_STATUS_REJECTED = 224;
    
    const MASK = 0xF0;
    
    const LW_RESPONSE_TYPE_XML = 0;
    const LW_RESPONSE_TYPE_JSON = 1;
    
    /**
     * Override if need
     * 
     * @var Integer
     */
    static protected $lwResponse = self::LW_RESPONSE_TYPE_XML;
    
    /**
     * @debug
     * @var boolean
     */
    static public $randomStatus = false;
    
    protected $_server;
    protected $_customerId;
    protected $_systemId;
    protected $_secretKey;
    protected $_moderationUrl;
    protected $_confirmationUrl;
    protected $_error;
    
    /**
     * @throws \LogicException
     * 
     * app.liveworld.all.server
     * app.liveworld.all.customerId
     * app.liveworld.all.systemId
     * app.liveworld.all.secretKey
     * 
     * @return array;
     */
    protected function getLwConfig()
    {
        if(!isset(App::$inst->config['app']['liveworld']['all']))
            throw new \LogicException('liveworld is not configured!');
        $config = App::$inst->config['app']['liveworld']['all'];
        return $config;
    }
    
    /**
     * 
     * @param unknown $em
     * @param Mapping\ClassMetadata $class
     * @throws \LogicException
     * @throws \Exception
     */
    public function __construct()
    {
        $config = $this->getLwConfig();
        if(!isset($config['server'])) 
            throw new \LogicException('app.liveworld.all.server are not configured!');
        if(!isset($config['customerId'])) 
            throw new \LogicException('app.liveworld.all.customerId are not configured!');
        if(!isset($config['systemId'])) 
            throw new \LogicException('app.liveworld.all.systemId are not configured!');
        if(!isset($config['secretKey'])) 
            throw new \LogicException('app.liveworld.all.secretKey are not configured!');
        
        $this->_server = $config['server'];
        $this->_customerId = $config['customerId'];
        $this->_systemId = $config['systemId'];
        $this->_secretKey = $config['secretKey'];
        
        
        //Request URI
        //https://crs-api.liveworld.com/v1/customers/<customername>/systems.json
        //https://crs-api.liveworld.com/v1/customers/<customername>/systems.xml
        
        $this->_error = null;
        
        switch (static::$lwResponse) {
            case static::LW_RESPONSE_TYPE_XML:
                $this->_moderationUrl = $this->_server . '/EndPointClientAxis2/rest/moderation_contents.xml';
                $this->_confirmationUrl = $this->_server . '/EndPointClientAxis2/rest/confirmations.xml';
                $this->_getItemUrl = $this->_server . '/EndPointClientAxis2/rest/moderation_contents/';
                break;
            case static::LW_RESPONSE_TYPE_JSON:
                $this->_moderationUrl = $this->_server . '/EndPointClientAxis2/rest/moderation_contents.xml';
                $this->_confirmationUrl = $this->_server . '/EndPointClientAxis2/rest/confirmations.xml';
                $this->_getItemUrl = $this->_server . '/EndPointClientAxis2/rest/moderation_contents/';
                throw new \Exception('json response method have not implemented yet',100); //this api not tested
                break;
            default:
                throw new \LogicException('wrong response method!');
                break;
        }
    }
    
    protected function xmlEntities($string) {
        return strtr(
            $string,
            array(
                "<" => "&lt;",
                ">" => "&gt;",
                '"' => "&quot;",
                "'" => "&apos;",
                "&" => "&amp;",
            )
        );
    }
    
    /**
     * @debug
     * @return Integer
     */
    public function getRandomLwModerationStatus()
    {
        return array_rand(
            array( 
                self::LW_MANUAL_STATUS_APPROVED => 'auto-approve', 
                self::LW_MANUAL_STATUS_REJECTED => 'auto-reject'
            )
        );
    }
    
    /**
     * 
     * @param \Entity\LwModerable $entry
     * @param string $moderationStatus
     * @return string xml
     */
    public function getXmlToSend(\Entity\LwModerable $entry, $moderationStatus = null)
    {
        $seed = $this->getSeed();
        $hash = $this->getHash($seed);
        $timestamp = time() * 1000;
        $type = $entry->getLwType();
        $mediaUrl = htmlspecialchars($entry->getLwMediaUrl());
        $link = htmlspecialchars($entry->getLwUrl());
        $moderationStatus = (!IS_PRODUCTION && !empty($moderationStatus)) ? $moderationStatus : 0;
        if(!IS_PRODUCTION && static::$randomStatus == true && $moderationStatus == 0){
            $moderationStatus = $this->getRandomLwModerationStatus();
        }
        
        $data = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>".
            "<com.liveworld.moderation.web.struts.rest.ModerationContent>".
            // required
        '<seed>'.$seed.'</seed>'. //The seed is the current timestamp in milliseconds.
        '<hash_value>'.$hash.'</hash_value>'. //The MD5 hash of the secret key (assigned by LiveWorld) concatenated with the seed.
        "<customer_id>$this->_customerId</customer_id>".
        "<system_id>$this->_systemId</system_id>".
        "<body>{$this->xmlEntities($entry->getLwBody())}</body>".
        "<subject>{$this->xmlEntities($entry->getLwSubject())}</subject>". //The subject of post, caption of images, or title of video.//'A photo by ' . htmlspecialchars($meta['first_name']);
        "<content_id>{$entry->getLwContentId()}</content_id>".
        "<tracking_id>{$entry->getLwTrackingId(true)}</tracking_id>".
        "<author_id>{$entry->getLwUserName()}</author_id>".
        "<content_time_stamp>$timestamp</content_time_stamp>".
        // optional
        "<author_ip></author_ip>".
        "<reporter_time_stamp>0</reporter_time_stamp>".
        "<locale></locale>".
        "<content_type>$type</content_type>".
        "<content_url>$mediaUrl</content_url>".
        "<moderation_status>$moderationStatus</moderation_status>". //208 auto approve, 224 auto reject
        "<type>0</type>".
        "<response_code>0</response_code>".
        "<time_stamp>0</time_stamp>".
        "<return_time_stamp>0</return_time_stamp>".
        "<nvpairs>".
        "<com.liveworld.moderation.web.struts.rest.Nvpair>".
        "<name>href</name>".
        "<value>$link</value>".
        "<id>" . mt_rand(100000, 999999) . "</id>".
        "</com.liveworld.moderation.web.struts.rest.Nvpair>".
        "</nvpairs>".
        "<reason_list/>".
        "</com.liveworld.moderation.web.struts.rest.ModerationContent>";
        return $data;
    }
    
    /**
     * 
     * @param \Entity\LwModerable $entry
     * @param string $moderationStatus
     * @return NULL | id
     */
    public function sendModerationContent(\Entity\LwModerable $entry, $moderationStatus = null)
    {
        $data = $this->getXmlToSend($entry,$moderationStatus);
//         App::$inst->log->debug('Send to Liveworld (xml): ' . $data);
    
        $curlHandle = curl_init($this->_moderationUrl);
        curl_setopt($curlHandle, CURLOPT_URL, $this->_moderationUrl);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
        'Content-type: text/xml; charset=utf-8'
            ));
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false); // for savvis
            $buffer = curl_exec($curlHandle);
            $responseHttpCode = (int) curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            curl_close($curlHandle);
//             App::$inst->log->debug('Received from Liveworld (httpcode and xml): '. $responseHttpCode . '   ' . $buffer);
            if ($responseHttpCode == 201) {
                $res = simplexml_load_string($buffer);
                if (empty($res->id)) {
                    $this->_error = $buffer;
                    return null;
                }
            } else {
                App::$inst->log->error(__METHOD__ . ': sent to liveworld returned http code ' . $responseHttpCode . ', body: ' . $buffer);
                return null;
            }
    
            return $res->id;
    }
    
    /**
     * 
     * @param string $xmlFile
     * @return Ambigous <array, NULL>
     */
    function getModerationContent($xmlFile = null)
    {
        if ($xmlFile) {
            $buffer = file_get_contents($xmlFile);
            $responseHttpCode = ($buffer !== false) ? 200 : 404;
        } else {
            $seed = $this->getSeed();
            $hash = $this->getHash($seed);
    
            $curlHandle = curl_init($this->_moderationUrl . '?seed=' . $seed . '&hash_value=' . $hash . '&system_id=' . $this->_systemId . '&customer_id=' . $this->_customerId);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false); // for savvis
            $buffer = curl_exec($curlHandle);
            if (APPLICATION_ENV == 'development') {
                file_put_contents('/tmp/' . time() . '.lw.xml', $buffer);
                file_put_contents(APPLICATION_PATH . '/tmp/liveworld/' . time() . '.lw.xml', $buffer);
            }
//             App::$inst->log->info('RECEIVED DATA FROM LIVEWORLD: ' . $buffer);
            $responseHttpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            curl_close($curlHandle);
        }
    
        if ($responseHttpCode == 200) {
            $result = (array) simplexml_load_string($buffer);
            $this->_error = $buffer;
        } else {
            $this->_error = $buffer;
            $result = null;
        }
    
        return $result;
    }
    
    /**
     *
     * @return bool
     */
    public function sendConfirmations($trackingIds)
    {
        if (! is_array($trackingIds) || empty($trackingIds)) {
            return false;
        }
    
        usleep(1);
        $seed = $this->getSeed();
        $hash = $this->getHash($seed);
        $trackingIdsStr = '';
        foreach ($trackingIds as $trackingId) {
            $trackingIdsStr .= '<string>' . $trackingId . '</string>';
        }
    
        $data = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>" . "<com.liveworld.moderation.web.struts.rest.Confirmation>" . "<seed>$seed</seed>" . "<hash__value>$hash</hash__value>" . "<customer__id>$this->_customerId</customer__id>" . "<system__id>$this->_systemId</system__id>" . "<tracking__id>$trackingIdsStr</tracking__id>" . "</com.liveworld.moderation.web.struts.rest.Confirmation>";
    
        $curlHandle = curl_init($this->_confirmationUrl);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array(
        'Content-type: text/xml; charset=utf-8'
            ));
            curl_setopt($curlHandle, CURLOPT_POST, 1);
            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curlHandle, CURLOPT_HEADER, true);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false); // for savvis
            $buffer = curl_exec($curlHandle);
            $responseHttpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            curl_close($curlHandle);
    
            return array(
                'code' => $responseHttpCode,
                'buffer' => $buffer
            );
    }
    
    public function getError()
    {
        return $this->_error;
    }
    
    public function resetError()
    {
        $this->_error = null;
    }
    
    /**
     *
     * @return string
     */
    protected function getSeed()
    {
        return number_format((microtime(true) * 1000), 0, '', '');
    }
    
    /**
     *
     * @return string
     */
    protected function getHash($seed)
    {
        return md5($this->_secretKey . $seed);
    }

}
