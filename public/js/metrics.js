!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.run(['$injector', function($injector){
    var
    $agent     = $injector.get('agent'),
    $config    = $injector.get('config'),
    $analytics = $injector.get('analytics'),
    $location  = $injector.get('$location'),
    $rootScope = $injector.get('$rootScope');

    //Analytics
    $rootScope.track = $analytics.init({
        //delimiter: ';', //default value is ';'
        //isolate: false, //default value is false
		account: $config.app.gaAccount,
        data: {
            tracks: {
                'view'                  : 'view;{{page}}',

                'page_home'             : '~',
                'page_about'            : '~',
                'popup_alert'           : '~',
                'video'                 : 'event;{{provider}};{{action}};{{id}}',
                'track1'                : 'view;/Page',
                'track2'                : '~event;Category;Event;Label;Value', //add ~ to use the key for funnel
                'track3'                : 'img;http://test.com/test.png?rnd={{timestamp}}',
                'track4'                : 'omniture;{"pageName":"Main Page","prop1":"Test","eVar8":"test@test.com","events":"event1"};t',

                'click'                 : 'event;click;{{label}};{{location}}', //number of clicks
                'submit'                : 'event;submit;submit;' + $agent.device, //number of users to submit their information
                'share'                 : 'event;share;{{type}};{{from}};' + $agent.device, //type - fb or tw, from - page where the share was clicked
                'return'                : 'event;shareReturn;{{type}};{{from}};' + $agent.device //type - fb or tw, from - page where the share was clicked
            },
            funnel: {
                'page_home;page_about'    : 'event;FunnelCategory;Event;Label;Value',
                'page_home;+;track2'      : 'event;FunnelMask+;Event;Label;Value', //page_home;(one track only);page_about
                'page_home;*;track2'      : 'event;FunnelMask*;Event;Label;Value'  //page_home;(multitracks);page_about
            }
        },
        vars: {phase: $config.app.phase},
        tracker: 'track' //name of tracker method and angular directive
    });

	//from js   - $rootScope.track('track name', {value:1} /*string or object*/, skipDoubleTrack);
	//from html - <span track="key name" /> or  <span track="['key name', {value:1}, true]" />

    //Views
    $rootScope.$on('$routeChangeSuccess', function(e, next, current){
        if (!next.$$route) {
            return;
        }
        $rootScope.track
        ('view', $location.path(), true)
        ('page_' + $location.path().replace(/^\/+/, ''), null, true);
    });
    //Popups
    $rootScope.$on('popup:open', function(e, $scope){
        $rootScope.track
        ('view', $scope.popupTpl, true)
        ('popup_' + $scope.popupTpl.replace(/.*\/([^\/]+).html$/, '$1'), null, true);
    });
    //Video
    $rootScope.$on('video', function(e, data){
        $rootScope.track('video', {provider:data.data.provider, id:data.data.id, action:data.type});
    });
    //Share returns
    if($config.fb.appDataObject && $config.fb.appDataObject.callback){
        $rootScope.track('return', {type: $config.fb.appDataObject.ref, from: $config.fb.appDataObject.callback});
    }
}]);

//------------------------------------------------------ end
}(angular);