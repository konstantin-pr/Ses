!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.controller('entry', ['$injector', '$scope', function($injector, $scope){
    var
    $config    = $injector.get('config'),
    $rootScope = $injector.get('$rootScope'),
    $http      = $injector.get('$http'),
    $tools     = $injector.get('tools');

    $scope.months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $scope.years = $tools.getRange(2014, 1914);

    $scope.submitEntry = function(entry) {
        angular.forEach(entry, function(value, key) {
            console.log(key + ': ' + value);
        });
        //$http.post('/entry', entry).then(function(response) {
        //    console.log(response);
        //});
    };

}]);

//------------------------------------------------------ end
}(angular);