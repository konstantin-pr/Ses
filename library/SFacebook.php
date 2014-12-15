<?php
namespace Library;

use Application\App;
use Application\Helpers\Device;
use Application\H;

class SFacebook
{

    static public function init()
    {

        App::$inst->container->singleton('fb', function () {
            $facebook = new \Facebook(App::$inst->config['facebook']);
            return $facebook;
        });
        $facebookRequest = App::$inst->fb->getSignedRequest();

        App::$inst->container->singleton('locale', function() use ($facebookRequest){
            return isset($facebookRequest['user']['locale']) ? $facebookRequest['user']['locale'] : 'en_US';
        });

        $endpoint = 'www.facebook.com/pages/PAGE/';
        $urlPage = $urlPageApp = '';

        if (isset($facebookRequest['page']['id'])) {
            $pageId = $facebookRequest['page']['id'];
            App::$inst->currentPageId = $pageId;
        } elseif (null !== ($requestIds = App::$inst->request->get('request_ids'))) {
            $pageId = self::getPageIdFromRequests($requestIds);
        } elseif (null === ($pageId = App::$inst->request->get('page_id'))) {
            // fallback to default page
            $pageId =  App::$inst->config['facebook']['pageId'];
        }

        if (!empty($pageId)) {
            $urlPage = PROTOCOL . $endpoint . $pageId;
            $urlPageApp = $urlPage . '?sk=app_' . App::$inst->config['facebook']['appId'];
        }

        App::$inst->pageId = $pageId;
        App::$inst->pageUrl = $urlPage;
        App::$inst->appPageUrl = $urlPageApp;
    }

    /**
     * FIXME: ...
     * Try to retrieve page_id from request_ids 'data' param
     *
     * @param string $requestIds $_GET parameter that FB sends to canvas
     *
     * @return string|null
     */
    static protected function getPageIdFromRequests($requestIds)
    {
        $requestIds = explode(',', $requestIds);
        $accessToken = static::getAccessToken();
        $response = json_decode(file_get_contents($url), true);
        if (isset($response['data'])) {
            $data = json_decode($response['data'], true);
            if (isset($data['page_id'])) {
                return $data['page_id'];
            }
        }
        return null;
    }
    
    static public function getAccessToken($grant_type = 'client_credentials')
    {
        $fbConfig = App::$inst->config['facebook'];
        $tokenUrl = "https://graph.facebook.com/oauth/access_token?" .
            "client_id=" . $fbConfig['appId'] .
            "&client_secret=" . $fbConfig['secret'] .
            "&grant_type=client_credentials";
        $accessToken = file_get_contents($tokenUrl);
        return $accessToken;
    }
    
    static public function isTab()
    {
        $signedRequest = App::$inst->fb->getSignedRequest();
        return !empty($signedRequest['page']['id']);
    }
    
    
    /**
     *
     */
    static public function getRedirectUrl()
    {
        if (!static::isTab() && !Device::device()->isMobile) {
            $page = App::$inst->appPageUrl;
        
            $appData = H::appData();
            if(!empty($appData)){
                $page.= "&app_data=".$appData;
            }
            return $page;
        } elseif( Device::device()->isMobile ) {
            $appData = H::appData();
            $page = '';
            if(!empty($appData)){
                $page.= "?app_data=".$appData;
            }
            return H::u('index/tab/'.$page);
        }
        return false;
    }
    
}
