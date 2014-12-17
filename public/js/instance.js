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
    .when('/entry', {templateUrl: '/html/' + $agent.device + '/entry.html', controller: 'entry'})
    .when('/products', {templateUrl: '/html/' + $agent.device + '/products.html'})
    .when('/final', {templateUrl: '/html/' + $agent.device + '/final.html'})
    .when('/thanks', {templateUrl: '/html/' + $agent.device + '/thanks.html'})
    .otherwise({redirectTo: '/home'});
}])

.run(['$injector', function($injector){
    var
    $config    = $injector.get('config'),
    $agent     = $injector.get('agent'),
    $tools     = $injector.get('tools'),
    //$facebook  = $injector.get('facebook'),
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
    $rootScope.popup = $popup.create('/html/' + $agent.device + '/popups/');
    //$rootScope.popupCustom = $popup.create('/html/customPopups/');

    // Two friendly ways to use the FB API,
    // as a promise or as a callback
    //$facebook.api('/me', function(){
    //    console.log(arguments[0]); //callback
    //}).then(function(){
    //    console.log(arguments[0]); //promise
    //});

    // Predefined methods
    // see facebook.js
    //$facebook.phototag(function(){
    //    console.log(arguments[0]);
    //});
    //$facebook.user(function(){
    //    console.log(arguments[0]);
    //});

}]);

//------------------------------------------------------ end
}(angular);