!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.config(['$injector', function($injector){
    var
    $config          = $injector.get('config'),
    $agent           = $injector.get('agent'),
    $cookie          = $injector.get('cookie'),
    $routeProvider   = $injector.get('$routeProvider');

    $routeProvider
    .when('/home', {templateUrl: '/html/desktop/home.html'})
    .when('/entry', {templateUrl: '/html/desktop/entry.html', controller: 'entry'})
    .when('/products', {templateUrl: '/html/desktop/products.html'})
    .when('/final1', {templateUrl: '/html/desktop/final1.html'})
    .when('/final2', {templateUrl: '/html/desktop/final2.html'})
    .when('/thanks', {templateUrl: '/html/desktop/thanks.html'})
    .otherwise({redirectTo: '/home'});
}])

.run(['$injector', function($injector){
    var
    $config    = $injector.get('config'),
    $agent     = $injector.get('agent'),
    $tools     = $injector.get('tools'),
    $facebook  = $injector.get('facebook'),
    $popup     = $injector.get('popup'),
    $timeout   = $injector.get('$timeout'),
    $rootScope = $injector.get('$rootScope');
    
    $rootScope.config = $config;

    //$rootScope.loginLinkedin = function(){
    //    $rootScope.oauth.loginLinkedin('75apyh2ggqrmjb', 'r_fullprofile').then(
    //        function(data){
    //            //console.log(data);
    //        },
    //        function(err){
    //            console.log(err);
    //            //err.code
    //        }
    //    );
    //};
    
    // Process
    // Show / Hide fullscreen preloader
    $rootScope.process = function(value){$timeout(function(){$rootScope.tags.set('process', Boolean(value));});};

    // Popups
    //$rootScope.popup = $popup.create('/html/' + $agent.device + '/popups/');
    $rootScope.popup = $popup.create('/html/desktop/popups/');
    //$rootScope.popupCustom = $popup.create('/html/customPopups/');

    // Two friendly ways to use the FB API,
    // as a promise or as a callback
    $facebook.api('/me', function(){
        console.log(arguments[0]); //callback
    }).then(function(){
        console.log(arguments[0]); //promise
    });

    // Predefined methods
    // see facebook.js
    //$facebook.phototag(function(){
    //    console.log(arguments[0]);
    //});
    //$facebook.user(function(){
    //    console.log(arguments[0]);
    //});

    var y = $config.app.videoUrl.indexOf(/youtu.?be/);
    if (y != -1) {
        y = $config.app.videoUrl.indexOf('youtube.com/watch');
        if (y != -1) {
            y = $config.app.videoUrl.indexOf(/\?*&*v=/);
            $rootScope.config.app.videoId = $config.app.videoUrl.substr(y + 3, 11);
        } else {
            y = $config.app.videoUrl.indexOf('youtube.com/embed/');
            if (y != -1) {
                $rootScope.config.app.videoId = $config.app.videoUrl.substr(y + 18, 11);
            } else {
                y = $config.app.videoUrl.indexOf('youtu.be/');
                if (y != -1) {
                    $rootScope.config.app.videoId = $config.app.videoUrl.substr(y + 9, 11);
                }
            }
        }
    } else {
        y = $config.app.videoUrl.indexOf('vimeo.com/');
        if (y != -1) {
            $rootScope.config.app.videoProvider = 'vimeo';
            $rootScope.config.app.videoId = $config.app.videoUrl.substr(y + 10);
        }
    }

}]);

//------------------------------------------------------ end
}(angular);