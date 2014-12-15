!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.service('gallery', ['$injector', function($injector){
    var
    $q         = $injector.get('$q'),
    $http      = $injector.get('$http');
    
    return (function(){
        var g = this;

        g.item = function(id){
            var deferred  = $q.defer();
            $http.post('/gallery/entry', {id:id}).then(function(response){
                if(!response || !response.data.success){
                    deferred.reject((response && response.data.error && response.data.error.message) || 'Something went wrong!');
                    return;
                };
                deferred.resolve(response.data.data || null);
            });
            return deferred.promise;
        };
        
        g.items = function(options){
            var deferred  = $q.defer(), options = angular.extend({offset: 0, limit: 9, filter: '', sort: ''}, options || {});
            $http.post('/gallery/list', options).then(function(response){
                if(!response || !response.data.success){
                    deferred.reject((response && response.data.error && response.data.error.message) || 'Something went wrong!');
                    return;
                };
                var list = response.data.data.list || [];
                angular.forEach(list, function(v, k){v.offset = options.offset + k;});
                deferred.resolve({
                    options: angular.extend(options, {total:response.data.data.total || 0}),
                    list: list
                });
            });
			return deferred.promise;
        };
        
        return g;
    }).call({})
}])

//------------------------------------------------------ end
}(angular);