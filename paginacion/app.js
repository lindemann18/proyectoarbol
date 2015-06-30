var app = angular.module('angularjs-starter', [ 'ui.bootstrap.pagination' ]);

app.factory( 'myData', function() {
  var data = [];
  
  // push some dummy data
  for(var i = 0; i < 30; i++) {
    data.push( { name: "item"+i } );
  }
  
  return {
    get: function(offset, limit) {
      return data.slice( offset, offset+limit );
    },
    count: function() {
      return data.length;
    }
  };
});

app.controller('MainCtrl', function($scope, myData) {
  $scope.numPerPage = 5;
  $scope.noOfPages = Math.ceil(myData.count() / $scope.numPerPage);
  $scope.currentPage = 1;

  $scope.setPage = function () {
    $scope.data = myData.get( ($scope.currentPage - 1) * $scope.numPerPage, $scope.numPerPage );
  };
  
  $scope.$watch( 'currentPage', $scope.setPage );
});