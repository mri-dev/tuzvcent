/**
* Dokumentumok
**/
var docs = angular.module('Documents', ['ngMaterial']);

docs.controller("List", ['$scope', '$http', function($scope, $http)
{
	$scope.docs = [];
	$scope.docs_inserted_ids = [];
	$scope.searchdocs = [];
	$scope.selectedItem = null;
	$scope.searcher = null;
	$scope.loading = false;
	$scope.termid = 0;
	$scope.error = false;

	$scope.init = function( id ){
		$scope.termid = id;
		$scope.loadDocsList( function( docs ){
			$scope.searchdocs = docs;
			$scope.loadList();
		} );
	}

	$scope.findSearchDocs = function( src ) {
		var result = src ? $scope.searchdocs.filter( $scope.filterForSearch( src ) ) : $scope.searchdocs;

		return result;
	}

	$scope.filterForSearch = function( query ){
		var lowercaseQuery = angular.lowercase(query);

    return function filterFn(item) {
      return (item.value.indexOf(lowercaseQuery) !== -1);
    };
	}

	$scope.searchTextChange = function(text) {
		console.log( 'searchTextChange: ' + text );
  }

	$scope.selectedItemChange = function( item )
	{
		if ( item && typeof item !== 'undefined' && typeof item.ID !== 'undefined') {
			var checkin = $scope.docs_inserted_ids.indexOf( parseInt(item.ID) );
			if ( checkin === -1 ) $scope.docs_inserted_ids.push(parseInt(item.ID));
		}

		if (typeof item !== 'undefined') {
			if ( checkin === -1 ) $scope.docs.push(item);
		}
	}

	$scope.loadDocsList = function( callback )
	{
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Documents",
        key: 'DocsList'
      })
    }).success(function( r ){
			console.log(r);
			if (typeof callback !== 'undefined') {

				callback( r.data.map(function(doc){
					doc.value = doc.cim.toLowerCase();
					return doc;
				}) );
			}
    });
	}

	$scope.loadList = function()
	{
		$scope.loading = true;
		$http({
      method: 'POST',
      url: '/ajax/get',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      data: $.param({
        type: "Documents",
        key: 'List',
				id: $scope.termid
      })
    }).success(function(r){
			$scope.loading = false;
			console.log(r);
			if (r.error == 0) {
				$scope.error = false;

			} else {
				$scope.error = r.msg;
			}
    });
	}
}]);
