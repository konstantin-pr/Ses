!function(angular){
'use strict';
(window.app || (window.app = angular.module('app', [])))
//------------------------------------------------------ start

.controller('entry', ['$injector', '$scope', function($injector, $scope){
    var
    $config    = $injector.get('config'),
    $rootScope = $injector.get('$rootScope'),
    $http      = $injector.get('$http'),
    $location  = $injector.get('$location'),
    $tools     = $injector.get('tools');

    $scope.months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $scope.years = $tools.getRange(2014, 1914);

    $scope.submitEntry = function(entry) {
        if (!entry) { return; }

        //age validation
        var now = new Date();
        var age = now.getFullYear() - entry.year;
        if ((age < 18) || (age === 18 && now.getMonth() < $scope.months.indexOf(entry.month))) {
            $rootScope.popup.show('alert.html', '<h1>Sorry</h1><p>You have to be 18 to be able to participate.</p>', {isFocusable: true});
            return;
        }

        var user = {
            firstName: entry.firstName || '',
            lastName: entry.lastName || '',
            email: entry.email || '',
            birthDate: entry.month + ' ' + entry.year,
            terms: !!entry.terms,
            rules: !!entry.rules,
            receiveEmails: !!entry.receiveEmails
        };
        $http.post('/registration', user).then(function(response) {
            if (!response || !response.data.success || response.data.error) {
                $rootScope.popup.show('alert.html', '<h1>Sorry</h1><p>' + ((response && response.data && response.data.error && response.data.error.message) ? response.data.error.message : 'Something went wrong.') + '</p>', {isFocusable: true});
                return;
            }
            switch (response.data.data.status) {
                case 'winner': $location.path('final1'); break;
                case 'not_a_winner': $location.path('final2'); break;
                case 'already_registered': $location.path('thanks'); break;
            }
        });
    };

}]);

//------------------------------------------------------ end
}(angular);