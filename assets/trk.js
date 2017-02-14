(function() {
    'use strict';
    angular.module('trk', [])
        .controller('trackController', ['$scope', '$http', '$timeout', function($scope, $http,$timeout) {
            var vm  = this;
            vm.ok = true;
            $http.get("api.php?page=codes")
                .then(function(response) {
                    vm.codes = response.data.codes;
                    vm.code = vm.codes[0].tracking_code
                }, function() {
                    vm.ok = false;
                });
            vm.check = function() {
                if(vm.code != ''){
                    $http.get("api.php?page=delivery-date&code="+vm.code)
                        .then(function(response) {
                            vm.delivery = response.data.delivery;
                            $timeout(function() {
                                vm.delivery = null;
                            }, 2000);
                        }, function (response) {
                            vm.error = response.data.error;
                            $timeout(function() {
                                vm.error = null;
                            }, 1500)
                        });
                }
            };
        }]);
})();
