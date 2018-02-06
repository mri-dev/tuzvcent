var tc = angular.module('tuzvedelmicentrum', ['ngMaterial', 'ngMessages']);

tc.controller('App', ['$scope', '$sce', '$http', '$mdToast', '$mdDialog', function($scope, $sce, $http, $mdToast, $mdDialog)
{
  $scope.fav_num = 0;
  $scope.fav_ids = [];
  $scope.in_progress_favid = false;
  $scope.requesttermprice = {};

  $scope.productAddToFav = function( id, ev ){
    var infav = $scope.fav_ids.indexOf(id);

    if ( infav !== -1 ) {
      var confirmRemoveFav = $mdDialog.confirm()
          .title('Biztos, hogy eltávolítja a kedvencekből?')
          .textContent('Ez a termék jelenleg a kedvencei közt szerepel.')
          .ariaLabel('Eltávolítás a kedvencek közül')
          .targetEvent(ev)
          .ok('Eltávolítás')
          .cancel('Mégse');

      $mdDialog.show(confirmRemoveFav).then(function() {
        $scope.doFavAction('remove', id, function(){
          $scope.syncFavs(function(err, n){
            $scope.fav_num = n;
            $scope.in_progress_favid = false;
          });
        });
      }, function() {

      });
    } else {
      $scope.in_progress_favid = id;
      $scope.doFavAction('add', id, function(){
        $scope.syncFavs(function(err, n){
          $scope.fav_num = n;
          $scope.in_progress_favid = false;
        });
      });
    }
  }

  $scope.init = function(){
    $scope.syncFavs(function(err, n){
      $scope.fav_num = n;
    });
  }

  $scope.productRemoveFromFav = function( id ){

  }

  $scope.requestPrice = function( id ){
    var confirm = $mdDialog.confirm({
			controller: RequestPriceController,
			templateUrl: '/app/templates/ProductItemPriceRequest',
			parent: angular.element(document.body),
			locals: {
        termid: id,
        requesttermprice: $scope.requesttermprice
			}
		});

    function RequestPriceController( $scope, $mdDialog, termid, requesttermprice) {
      $scope.sending = false;
      $scope.termid = termid;
      $scope.requesttermprice = requesttermprice;

			$scope.closeDialog = function(){
				$mdDialog.hide();
			}

      $scope.validateForm = function(){
        var state = false;
        var phone_test = ''

        if (
          (typeof $scope.requesttermprice.name !== 'undefined' && $scope.requesttermprice.name.length >= 5) &&
          (typeof $scope.requesttermprice.phone !== 'undefined' && !$scope.requesttermprice.phone.$error) &&
          (typeof $scope.requesttermprice.email !== 'undefined' && !$scope.requesttermprice.email.$error)
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
              datas: {
                termid: $scope.termid
              }
      			})
      		}).success(function(r){
      			console.log(r);
      			$scope.sending = false;

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

    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "getTermItem",
        id: id
      })
    }).success(function(r){
      console.log(r);
      if (r.error == 1) {
        $scope.toast(r.msg, 'alert', 10000);
      } else {
        $scope.requesttermprice.product = r.product;
        $mdDialog.show(confirm)
    		.then(function() {
          $scope.status = 'You decided to get rid of your debt.';
        }, function() {
          $scope.status = 'You decided to keep your debt.';
        });
      }
    });

  }

  $scope.doFavAction = function( type, id, callback ){
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "productFavorite",
        action: type,
        tid: id
      })
    }).success(function(r){
      if (r.error == 1) {
        $scope.toast(r.msg, 'alert', 10000);
      } else {
        $mdToast.hide();
        $scope.toast(r.msg, 'success', 5000);
      }

      if (typeof callback === 'function') {
        callback(r.error, r.msg, r);
      }
    });
  }

  $scope.syncFavs = function( callback ){
    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "productFavorite",
        action: 'get',
        own: 1
      })
    }).success(function(r){
      if (r.ids) {
        $scope.fav_ids = [];
        angular.forEach(r.ids, function(v,i){
          $scope.fav_ids.push(v);
        });
      }
      if (typeof callback === 'function') {
        callback(r.error, r.num);
      }
    });
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

}]);

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

tc.filter('unsafe', function($sce){ return $sce.trustAsHtml; });
