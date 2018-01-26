var tc = angular.module('tuzvedelmicentrum', ['ngMaterial', 'ngMessages']);

tc.controller('ActionButtons', ['$scope', '$http', '$mdDialog', function($scope, $http, $mdDialog){

  $scope.showHints = true;
  $scope.recall = {};

  $scope.requestRecall = function(){
		var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/recall',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        recall: $scope.recall
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, recall) {
      $scope.showHints = showHints;
      $scope.recall = recall;
			$scope.closeDialog = function(){
				$mdDialog.hide();
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
