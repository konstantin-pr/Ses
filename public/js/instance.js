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
    .when('/home', {templateUrl: '/html/' + $agent.device + '/home.html'})
    .when('/about', {templateUrl: '/html/' + $agent.device + '/about.html', resolve:{
        someData: ['$http', function($http){
            return [1, 2, 3]; //$http.get('http://some.data.com');
        }]
    }})
    .when('/gallery/:page/:filter/:sort', {templateUrl: '/html/' + $agent.device + '/gallery.html', controller:'gallery', resolve:{
		entrys: ['$injector', function($injector){
            var
            perPage     = 6,
            $gallery    = $injector.get('gallery'),
            $route      = $injector.get('$route');
            var options = {
                limit: perPage,
                offset: (($route.current.params.page - 1) || 0) * perPage,
                filter: $route.current.params.filter || 'all',
                sort: $route.current.params.sort || 'recent'
            };
            return $gallery.items(options);
		}]
    }})
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
    
    $rootScope.loginLinkedin = function(){
        $rootScope.oauth.loginLinkedin('75apyh2ggqrmjb', 'r_fullprofile').then(
            function(data){
                //console.log(data);
            },
            function(err){
                console.log(err);
                //err.code
            }
        );
    };
    
    // Process
    // Show / Hide fullscreen preloader
    $rootScope.process = function(value){$timeout(function(){$rootScope.tags.set('process', Boolean(value));});};
    
    // Popups
    $rootScope.popup = $popup.create('/html/' + $agent.device + '/popups/');
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
    $facebook.phototag(function(){
        console.log(arguments[0]);
    });
    $facebook.user(function(){
        console.log(arguments[0]);
    });
    
}]);

//------------------------------------------------------ end
}(angular);