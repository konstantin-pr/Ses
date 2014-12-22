<?php
    use
    \Application\App,
    \Application\H,
    \Application\Helpers\Resource,
    \Application\Helpers\AppData;
?>
<!DOCTYPE html>
<html lang="en" xmlns:ng="http://angularjs.org" class="app-loading">
<head>
    <!--[if lt IE 9]>
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
    <![endif]-->
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Stuzo" />
    <meta name="copyright" content="Stuzo" />

    <meta name="viewport" content="width=810, target-densityDpi=device-dpi" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <!-- <meta name="apple-itunes-app" content="app-id=XXXXXXXXX" /> -->
    <meta name="format-detection" content="address=no" />
    <meta name="format-detection" content="telephone=no" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="msapplication-tap-highlight" content="no" />
    <meta name="MobileOptimized" content="810" />        
    
    <link rel="apple-touch-icon" href="/img/57x57.png" />
    <link rel="apple-touch-icon-precomposed" href="/img/57x57.png" />
    <link rel="icon" type="image/png" href="/img/16x16.png" />
    <link rel="shortcut" type="image/png" href="/img/57x57.png" />

    <meta property="fb:app_id" content="<?php echo H::vars()->fb->appId; ?>" />
    <meta property="og:url" content="<?php echo H::vars()->fb->canvasUrl; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:image" content="<?php echo H::vars()->app->url; ?>/img/200x200.png" />

    <!--[if lt IE 9]>
    <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!--[if lte IE 8]>
    <script>
        document.createElement('ng-include');
        document.createElement('ng-pluralize');
        document.createElement('ng-view');
        document.createElement('ng:include');
        document.createElement('ng:pluralize');
        document.createElement('ng:view');
    </script>
    <![endif]-->
    <!--[if lt IE 8]>
    <script src="//cdnjs.cloudflare.com/ajax/libs/json2/20121008/json2.min.js"></script>
    <![endif]-->

    <title></title>

    <!-- build:css /css/bin/app.min.css -->
    <link rel="stylesheet" href="/css/reset.css" />
    <link rel="stylesheet" href="/css/styles.css" />
    <!-- endbuild -->
</head>

<body ontouchstart="" ng-cloak="true" class="ng-cloak">
    <div id="view" ng-view="true"></div>
    <div class="process"><div></div></div>
    <?php echo H::savvisInject(); ?>
</body>

<!-- build:js /js/bin/lib.min.js -->
<script type="text/javascript" src="/js/lib/angular/angular.js"></script>
<script type="text/javascript" src="/js/lib/angular/angular-route.js"></script>
<script type="text/javascript" src="/js/lib/angular/angular-animate.js"></script>
<!--<script type="text/javascript" src="/js/lib/angular/angular-resource.js"></script>-->
<!--<script type="text/javascript" src="/js/lib/angular/angular-mocks.js"></script>-->
<!-- endbuild -->

<script type="text/javascript">
window.appConfig = <?php echo H::vars(true); ?>;
window.appModules = function(){
    var l = Array.prototype.slice.call(arguments, 0), i = 0;
    l = l.length ? l : ['ngRoute', 'ngAnimate', 'ngResource'];
    while(i < l.length){try{angular.module(l[i]); i++}catch(e){l.splice(i, 1);}}
    return l;
};
angular.extend(window.appConfig.fb, {
    isTab:      !!(/^app_runner/).test(window.name),
    isCanvas:   !!(/^iframe_canvas/).test(window.name),
    isMobile:   !!(/^fbforiphone/).test(window.navigator.userAgent),
    useMobile:  false,
    detectFan:  false,
    onInit:     function(angular){angular.bootstrap(document.documentElement, ['app']);}
});
(window.app || (window.app = angular.module('app', window.appModules()))).constant('config', window.appConfig);
</script>

<?php //H::copyData(); ?>
<!-- build:js /js/bin/app.min.js -->
<script type="text/javascript" src="/js/lib/analytics.js"></script>
<script type="text/javascript" src="/js/lib/application.js"></script>
<script type="text/javascript" src="/js/lib/video.js"></script>
<!--<script type="text/javascript" src="/js/lib/facebook.js"></script>-->
<!--<script type="text/javascript" src="/js/lib/oauth.js"></script>-->

<script type="text/javascript" src="/js/controllers.js"></script>
<script type="text/javascript" src="/js/instance.js"></script>
<script type="text/javascript" src="/js/metrics.js"></script>
<script type="text/javascript" src="/js/services.js"></script>
<!--<script type="text/javascript" src="/js/mock/mock.js"></script>-->
<!-- endbuild -->
<?php H::vars()->fb->debug && H::debug(); ?>

<script>typeof(window['fbAsyncInit'])=='undefined'&&window.appConfig.fb.onInit(angular);</script>
</html>