angular.module('artifactFactory', [])
       .factory('artifacts', function($http){
  return {
    list: function (callback){
      $http({
        method: 'GET',
         url: 'public_html/artifacts.php',
        cache: true
      }).success(callback);
    },
    find: function(id, callback){
      $http({
        method: 'GET',
        url: 'public_html/artifacts.php?id=' + id,
        cache: true
      }).success(callback);
    }
  };
});