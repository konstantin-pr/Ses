!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.controller('gallery', ['$injector', '$scope', 'entrys', function($injector, $scope, entrys){
    var
    $config    = $injector.get('config'),
    $tools     = $injector.get('tools'),
    $gallery   = $injector.get('gallery');
    
    $scope.options = entrys.options;
    $scope.list = entrys.list;
    $scope.perPage = 6;
    $scope.pageCount = 3;
    $scope.pages = Math.ceil($scope.options.total / $scope.perPage);
    $scope.page = ($scope.options.offset / $scope.perPage >> 0) + 1;
    $scope.prevRange = ($scope.page > 1 ? $tools.getRange($scope.page - 1, Math.max(1, $scope.page - $scope.pageCount)) : []).reverse();
    $scope.nextRange = $scope.page < $scope.pages ? $tools.getRange($scope.page + 1, Math.min($scope.pages, $scope.page + $scope.pageCount)) : [];
}])

//------------------------------------------------------ end
}(angular);