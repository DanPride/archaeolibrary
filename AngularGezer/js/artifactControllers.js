var artifactControllers = angular.module('artifactControllers', ['ngAnimate']);

artifactControllers.controller('ArtifactListCtrl', function($scope, artifacts) {
	artifacts.list(function(artifacts){
  	$scope.artifacts = artifacts;
    $scope.artifactOrder = 'Locus';
    $scope.totalDisplayed = 100;
    $scope.wait = $scope.artifacts.length;
    $scope.avail = " - of - ";
  });
});

artifactControllers.controller('ArtifactDetailCtrl', function($scope, $routeParams, artifacts) {
  console.log($routeParams);
 	artifacts.find($routeParams.itemId, function(data){
   // var country = data.filter(function(entry){
   //  log(entry.name);
   //      log($scope.name);
   //          return entry.name === $scope.name;
   //        }})[0];

   //        console.log(country);
 		$scope.artifacts = data;
    	$scope.whichItem = $routeParams.itemId;
 

    if ($routeParams.itemId > 0) {
      $scope.prevItem = Number($routeParams.itemId)-1;
    } else {
      $scope.prevItem = $scope.artifacts.length-1;
    }

    if ($routeParams.itemId < $scope.artifacts.length-1) {
      $scope.nextItem = Number($routeParams.itemId)+1;
    } else {
      $scope.nextItem = 0;
    }

  });
});
