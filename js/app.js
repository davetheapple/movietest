/*
	Candidate App
	Gets a movie list from The Movie DB and displays it
*/

angular.module('MovieApp', ['datatables'])

// create a service to handle all the interaction with the server and movie db
.service("apiService", function($http, $q) {

	this.api = function(url, post) {

        var deferred = $q.defer();

        // POST it
        $http.post(url, post)
        .then(function(data) {
        	console.log(data);
        	deferred.resolve(data.data);
        })

        // did we mess up
        .catch(function(error) {
            console.log(error);
            deferred.reject(error);
        });

        // promise that you will return my data to its proper place at a later time
        return deferred.promise;
    }
})

// main controller to run everything
.controller("movieController", function($scope, $http, $timeout, $compile, DTColumnBuilder, DTOptionsBuilder, apiService) {
	var vm = this;///WorkProjects/CandidateTest/
	var promise = apiService.api("/barrerad/helper.php", {GetData: 1});

	$scope.theList = [];

	// display only these columns
	vm.dtColumns = [
        DTColumnBuilder.newColumn('title').withTitle('Title'),
        DTColumnBuilder.newColumn('release_date').withTitle('Release Date'),
        DTColumnBuilder.newColumn('vote_count').withTitle('Vote Count')
    ];
    
    // set table options
    vm.dtOptions = DTOptionsBuilder.fromFnPromise(promise)
    .withPaginationType('full_numbers')
    .withOption('order', [[2, 'desc']])
    .withOption('pageLength', 50);

    // set up click events
    promise.then(function(data) {
    	$scope.theList = data;

    	// not the angular way but still effective
    	// directive didnt seem to work 
    	$timeout(function() { 
	    	$(".movie").each(function(idx, item) {
	    		$(item).on("click", function() {
	    			for(var i = 0; i < $scope.theList.length; i++){
	    				if($scope.theList[i].id == $(this).data("item")) {
	    					//$timeout(function() { $scope.selected = $scope.theList[i]; },500); // terrible
	    					apiService.api("/barrerad/helper.php", {GetMovie: $scope.theList[i].id}).then(function(data) { $scope.selected = data; });
	    					break;
	    				}
	    			}
	    		});
	    	});
	    	$scope.$apply();
	    },500);
    });
   
})

.directive("openEvent", function () {
    return {
        link: function ($scope) {
        	// i don't have much time to solve this, mostly 
        	// its probably because of the digest and in a $timeout everything is updated
            jQuery("a.movie").on("click", function (event) {
                console.log($(this).html());
            });
        }
    }
});
