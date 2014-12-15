!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.config(['$injector', function($injector){
    var
    logError         = console ? console.error || console.log : function(){},
    $config          = $injector.get('config'),
    $httpProvider    = $injector.get('$httpProvider');

    $config.environment != 'pruduction' && $httpProvider.interceptors.push(function(){
        return {
            responseError: function(rejection){
                logError.call(console, rejection);
            },
            requestError: function(rejection){
                logError.call(console, rejection);
            }
        };
    });
}])

.run(['$injector', function($injector){
    var
    logError   = console ? console.error || console.log : function(){},
    $config    = $injector.get('config');
    
    if($config.environment != 'pruduction'){
        //window.onerror = function(msg, url, line){logError.call(console, 'JS Error', msg, url + ' : ' + line); return false;};
        window.onjQueryError = function(message){logError.call(console, 'jQuery Error', message);};
        window.onjQueryAjaxError = function(status, responseText){logError.call(console, 'jQuery Ajax Error', status, responseText);};
        window.onFBError = function(status, responseText){logError.call(console, 'FB Error', status, responseText);};
        if(window.jQuery){
            jQuery.error = window.onjQueryError;
            jQuery.ajaxSetup({error: window.onjQueryAjaxError});
        };
    };
}]);

//------------------------------------------------------ end
}(angular);