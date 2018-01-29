var tc = angular.module('tuzvedelmicentrum', ['ngMaterial', 'ngMessages']);

tc.controller('ActionButtons', ['$scope', '$http', '$mdDialog', '$mdToast', function($scope, $http, $mdDialog, $mdToast){

  $scope.showHints = true;
  $scope.recall = {};
  $scope.ajanlat = {};

  /**
  * Ingyenes visszahívás modal
  **/
  $scope.requestRecall = function(){
		var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/recall',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        recall: $scope.recall,
        ajanlat: $scope.ajanlat
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, recall, ajanlat) {
      $scope.showHints = showHints;
      $scope.recall = recall;
      $scope.ajanlat = ajanlat;
      $scope.sending = false;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.recall.name !== 'undefined' && $scope.recall.name.length >= 5) &&
          (typeof $scope.recall.phone !== 'undefined' && !$scope.recall.phone.$error) &&
          (typeof $scope.recall.subject !== 'undefined')
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope[type]
      			})
      		}).success(function(r){
      			console.log(r);
      			$scope.sending = false;
      			$scope.recall = {};

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast('Köszönjük érdeklődését! Ingyenes visszahívás kérés igénylés elküldve. Hamarosan jelentkezünk!', 'success', 10000);
      			}
      		});
        }
      }
      $scope.toast = function( text, mode, delay ){
    		mode = (typeof mode === 'undefined') ? 'simple' : mode;
        delay = (typeof delay === 'undefined') ? 5000 : delay;

    		if (typeof text !== 'undefined') {
    			$mdToast.show(
    				$mdToast.simple()
    				.textContent(text)
    				.position('top')
    				.toastClass('alert-toast mode-'+mode)
    				.hideDelay(delay)
    			);
    		}
    	}
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }
  /**
  * Ajánlatkérés modal
  **/
  $scope.requestAjanlat = function()
  {
    var confirm = $mdDialog.confirm({
			controller: ConfirmPackageOrder,
			templateUrl: '/app/templates/ajanlatkeres',
			parent: angular.element(document.body),
			locals: {
        showHints: $scope.showHints,
        ajanlat: $scope.ajanlat
			}
		});

		function ConfirmPackageOrder( $scope, $mdDialog, showHints, ajanlat) {
      $scope.showHints = showHints;
      $scope.ajanlat = ajanlat;
      $scope.sending = false;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}
      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.ajanlat.name !== 'undefined' && $scope.ajanlat.name.length >= 5) &&
          (typeof $scope.ajanlat.phone !== 'undefined' && !$scope.ajanlat.phone.$error) &&
          (typeof $scope.ajanlat.email !== 'undefined' && !$scope.ajanlat.email.$error) &&
          (typeof $scope.ajanlat.message !== 'undefined')
        ) {
          state = true;
        }

        return state;
      }

      $scope.sendModalMessage = function( type ){
        if (!$scope.sending) {
          $scope.sending = true;

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope[type]
      			})
      		}).success(function(r){
      			console.log(r);
      			$scope.sending = false;
      			$scope.ajanlat = {};

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast('Köszönjük érdeklődését! Hamarosan jelentkezünk személyre szabott ajánlatunkkal!', 'success', 10000);
      			}
      		});
        }
      }
      $scope.toast = function( text, mode, delay ){
    		mode = (typeof mode === 'undefined') ? 'simple' : mode;
        delay = (typeof delay === 'undefined') ? 5000 : delay;

    		if (typeof text !== 'undefined') {
    			$mdToast.show(
    				$mdToast.simple()
    				.textContent(text)
    				.position('top')
    				.toastClass('alert-toast mode-'+mode)
    				.hideDelay(delay)
    			);
    		}
    	}
		}

		$mdDialog.show(confirm)
		.then(function() {
      $scope.status = 'You decided to get rid of your debt.';
    }, function() {
      $scope.status = 'You decided to keep your debt.';
    });
  }


}]);
