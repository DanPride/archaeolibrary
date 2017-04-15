var photoControllers = angular.module('photoControllers', ['ngAnimate']);

photoControllers.controller('PhotoListCtrl', function($scope, photos) {
	photos.list(function(photos){
  	$scope.photos = photos;
    $scope.photoOrder = 'Locus';
    $scope.totalDisplayed = 100;
    $scope.wait = $scope.photos.length;
    $scope.avail = " - of - ";
  });
});

photoControllers.controller('PhotoDetailCtrl', function($scope, $routeParams, photos) {
 // log.console($routeParams);
 	photos.find($routeParams.itemId, function(data){
    // var photo = data.filter(function(entry){
    //          return entry.Name === $scope.Name;
    //        }})[0];
 		$scope.photos = data;
    	$scope.whichItem = $routeParams.itemId;
 

    if ($routeParams.itemId > 0) {
      $scope.prevItem = Number($routeParams.itemId)-1;
    } else {
      $scope.prevItem = $scope.photos.length-1;
    }

    if ($routeParams.itemId < $scope.photos.length-1) {
      $scope.nextItem = Number($routeParams.itemId)+1;
    } else {
      $scope.nextItem = 0;
    }

  });
});











