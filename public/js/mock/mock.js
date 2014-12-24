!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.run(['$injector', function($injector){
    var
    $config         = $injector.get('config'),
    $tools         = $injector.get('tools'),
    $httpBackend    = $injector.get('$httpBackend');
    
    var item = function(){
        return {id: $tools.getRandom(1, 10000), src: 'http://placekitten.com/200/200?image=' + $tools.getRandom(1, 10)};
    };
    var items = function(count){
        var list = []; count = count * 1;
        for(var i = 0; i < count; i++){
            list.push(item());
        }
        return list;
    };
    
    $httpBackend.whenPOST('/gallery/list').respond(function(method, url, data, headers){
        var requestData = $tools.getUrlParams(data);
        var respondData = {
            success: true,
            error: {code:0, message:''},
            data: {total:100, list:items(requestData.limit)}
        };
        return [200, respondData, {}];
    });
    
    //Don't mock for everything else 
    $httpBackend.whenJSONP(/.*/).passThrough();
    $httpBackend.whenGET(/.*/).passThrough();
    $httpBackend.whenPOST(/.*/).passThrough();
    $httpBackend.whenPUT(/.*/).passThrough();
    $httpBackend.whenDELETE(/.*/).passThrough();
}]);

//------------------------------------------------------ end
}(angular);