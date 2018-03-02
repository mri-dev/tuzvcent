var tc = angular.module('tuzvedelmicentrum', ['ngMaterial', 'ngMessages', 'ngCookies']);

tc.controller('App', ['$scope', '$sce', '$http', '$mdToast', '$mdDialog', '$location','$cookies', '$cookieStore', function($scope, $sce, $http, $mdToast, $mdDialog, $location, $cookies, $cookieStore)
{
  $scope.fav_num = 0;
  $scope.fav_ids = [];
  $scope.in_progress_favid = false;
  $scope.requesttermprice = {};
  $scope.order_accepted = false;
  $scope.accept_order_key = 'acceptedOrder';
  $scope.accept_order_text = null;
  $scope.accept_order_title = 'Szerződési feltételek elfogadása';

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

  $scope.init = function( ordernow ){
    $scope.syncFavs(function(err, n){
      $scope.fav_num = n;
    });

    if (typeof ordernow !== 'undefined' && ordernow === true ) {
      $scope.loadSettings( ['tuzvedo_order_pretext','tuzvedo_order_pretext_wanted','tuzvedo_order_pretext_title'], function(settings){
        if (settings.tuzvedo_order_pretext_wanted == '1') {
          $scope.accept_order_title = (settings.tuzvedo_order_pretext_title != '') ? settings.tuzvedo_order_pretext_title : $scope.accept_order_title ;
          $scope.accept_order_text = settings.tuzvedo_order_pretext;
          $scope.acceptBeforeDoneOrder();
        } else {
          $scope.order_accepted = true;
        }
      });
    }
  }

  $scope.loadSettings = function( key, callback ){
    $http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "settings",
        key: key
      })
    }).success(function(r){
      callback(r.data);
    });
  }

  $scope.productRemoveFromFav = function( id ){

  }

  $scope.acceptBeforeDoneOrder = function(){
    var accepted = $cookieStore.get( $scope.accept_order_key );

    if ( typeof accepted === 'undefined' )
    {
      var confirm = $mdDialog.confirm({
  			controller: acceptBeforeDoneOrderController,
  			templateUrl: '/app/templates/acceptBeforeDoneOrder',
        scope: $scope,
        preserveScope: true,
  			parent: angular.element(document.body),
        locals: {
          order_accepted: $scope.order_accepted,
          accept_order_key: $scope.accept_order_key
        }
  		});

      function acceptBeforeDoneOrderController( $scope, $mdDialog, order_accepted, accept_order_key) {
        $scope.order_accepted = order_accepted;
        $scope.accept_order_key = accept_order_key;

  			$scope.closeDialog = function(){
  				$mdDialog.hide();
  			}
        $scope.acceptOrder = function(){
          $cookies.put($scope.accept_order_key, 1);
          $scope.order_accepted = true;
          $mdDialog.hide();
  			}
  		}
      $mdDialog.show(confirm);
    } else {
      $scope.order_accepted = true;
    }
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

          $scope.requesttermprice.termid = parseInt($scope.termid);

          $http({
      			method: 'POST',
      			url: '/ajax/post',
      			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      			data: $.param({
      				type: "modalMessage",
              modalby: type,
              datas: $scope.requesttermprice
      			})
      		}).success(function(r) {
      			$scope.sending = false;
            $scope.requesttermprice = {};
            console.log(r);

      			if (r.error == 1) {
      				$scope.toast(r.msg, 'alert', 10000);
      			} else {
      				$mdToast.hide();
              $scope.closeDialog();
      				$scope.toast(r.msg, 'success', 10000);
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
      				$scope.toast(r.msg, 'success', 10000);
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

            console.log(r);

            if (r.error == 1) {
              $scope.toast(r.msg, 'alert', 10000);
            } else {
              $mdToast.hide();
              $scope.closeDialog();
              $scope.toast(r.msg, 'success', 10000);
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

tc.controller('Tudastar',['$scope', '$http', '$mdToast', '$element', function($scope, $http, $mdToast, $element)
{
  $scope.found_items = 0;
  $scope.loading = false;
  $scope.loaded = false;
  $scope.categories = [];
  $scope.searchKeys = [];
  $scope.validitems = [];
  $scope.catFilters = {};
  $scope.selected_article = 0;
  $scope.precats = false;
  $scope.picked_article = false;

  $scope.init = function( picked_article_id, tags, cats )
  {
    $scope.selected_article = picked_article_id;
    $scope.precats = cats;

    if (tags != '') {
      var xtags = tags.split(',');
      if (typeof xtags !== 'undefined') {
        angular.forEach(xtags, function(tag,i){
          $scope.putTagToSearch(tag);
        });
      }
    }

    $scope.loadCategories(function( success ){
      $scope.loaded = true;
      $scope.loading = false;

      var cats = $scope.precats;
      if (cats != '') {
        var cats = cats.split(',');
        if (typeof cats !== 'undefined') {
          angular.forEach(cats, function(cat,i){
            if( !$scope.catInFilter(parseInt(cat)) ) {
              $scope.filterCategory(cat);
            }
          });
        }
      }

      if ( $scope.selected_article != '0' ) {
        $scope.picked_article = $scope.findArticle( $scope.selected_article );
        console.log($scope.picked_article);
      }

    });

  }

  $scope.findArticle = function( article )
  {
    var obj;

    if ( $scope.categories.length != 0 ) {
        angular.forEach( $scope.categories, function(cat, i){
          if (cat.articles.length != 0) {
            angular.forEach( cat.articles, function(art, i){
              if (art.ID == article) {
                obj = art;
              }
            });
          }
        });
    }

    return obj;
  }

  $scope.doSearch = function(){
    $scope.loadCategories(function( success ){
      $scope.loaded = true;
      $scope.loading = false;
    });
  }

  $scope.catInFilter = function( catid ){
    var isin = false;
    if ( $scope.catFilters.length != 0 ) {
      angular.forEach( $scope.catFilters, function(cf, i){
        if( cf.ID == catid){
          isin = true;
        }
      });
    }

    return isin;
  }

  $scope.emptyCatFilters = function(){
    if ( angular.equals({}, $scope.catFilters)) {
      return true;
    }else {
      return false;
    }
  }

  $scope.getCatData = function(catid) {
    var obj;

    if ( $scope.categories.length != 0 ) {
        angular.forEach( $scope.categories, function(cat, i){
          if( cat.ID == catid ) {
            obj = cat;
          }
        });
    }

    return obj;
  }

  $scope.filterCategory = function( catid )
  {
    var key = 'cat' + catid;
    if ( typeof $scope.catFilters[key] === 'undefined') {
      $scope.catFilters[key] = {};
      $scope.catFilters[key] = $scope.getCatData( catid );
    } else {
      delete $scope.catFilters[key];
    }

    $scope.doSearch();
  }

  $scope.putTagToSearch = function( tag ){
    if ( $scope.searchKeys.indexOf(tag) === -1) {
      $scope.searchKeys.push(angular.lowercase(tag));
    }
  }

  $scope.inSearchTag = function(tag){
    if ( $scope.searchKeys.indexOf(tag) === -1) {
      return false;
    } else {
      return true;
    }
  }

  $scope.pickArticle = function( articleid ){
    $scope.selected_article = articleid;
  }

  $scope.loadCategories = function( callback ){
    $scope.loading = true;
    $scope.loaded = false;

    $http({
      method: 'POST',
      url: '/ajax/post',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Helpdesk",
        action: 'getCategories',
        search: $scope.searchKeys,
        cats: $scope.catFilters
      })
    }).success(function(r){
      if (r.success == 1) {
        $scope.categories = r.data;
        $scope.found_items = r.count;
      } else {
        $scope.toast( r.msg , 'alert', 10000);
      }

      if (typeof callback !== 'undefined') {
        callback(r.success);
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

tc.filter('unsafe', function($sce){ return $sce.trustAsHtml; });
