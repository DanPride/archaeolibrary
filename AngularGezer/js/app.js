var myApp = angular.module('myApp', [
  'ngRoute',
  'artifactControllers', 
  'artifactFactory' ,
  'photoControllers', 
  'photoFactory'
]);

myApp.config(['$routeProvider', function($routeProvider) {
  $routeProvider.
  when('/list', {
    templateUrl: 'partials/list.html',
    controller: 'ArtifactListCtrl'
  }).
  when('/photos', {
    templateUrl: 'partials/photos.html',
    controller: 'PhotoListCtrl'
  }).
  when('/photo/:itemId', {
    templateUrl: 'partials/photo.html',
    controller: 'PhotoDetailCtrl'
  }).
  when('/details/:itemId', {
    templateUrl: 'partials/details.html',
    controller: 'ArtifactDetailCtrl'
  }).
  otherwise({
    redirectTo: '/list'
  });
}]);


// myApp.directive('photo', function() {
//     return {
//         restrict: 'E',
//         templateUrl: 'photo.html',
//         replace: true,
//         // pass these two names from attrs into the template scope
//         scope: {
//             caption: '@',
//             photoSrc: '@'
//         }
//     }
// })