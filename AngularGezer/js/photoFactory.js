angular.module('photoFactory', [])
       .factory('photos', function($http){
  return {
    list: function (callback){
      $http({
        method: 'GET',
         url: 'public_html/photos.php',
        cache: true
      }).success(callback);
    },
    find: function(Name, callback){
            var theid = 'public_html/photo.php?Name=' + Name;
            console.log(theid);
      $http({
        method: 'GET',
        url: theid,
        cache: true
      }).success(callback);
    }
  };
});