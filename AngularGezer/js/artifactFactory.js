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
    find: function(Name, callback){
            var theid = 'public_html/artifact.php?Name=' + Name;
      $http({
        method: 'GET',
        url: theid,
        cache: true
      }).success(callback);
    }
  };
});