var tc = angular.module('tuzvedelmicentrum', ['ngMaterial']);

tc.controller('ActionButtons', ['$scope', '$http', '$mdDialog', function($scope, $http, $mdDialog){

  $scope.requestRecall = function(){
		var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/api/template/confirm_package_order',
			parent: angular.element(document.body),
			locals: {
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, csomag, me) {
			$scope.csomag = csomag;
			$scope.me = me;
			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
			$scope.topup = function(){
				$window.location.href = '/egyenleg';
			}
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }
  $scope.requestAjanlat = function(){}

}]);
