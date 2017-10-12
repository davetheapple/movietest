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

.controller("movieController", function($scope, $http, DTColumnBuilder, DTOptionsBuilder, apiService) {
	var vm = this;

	vm.dtColumns = [
        DTColumnBuilder.newColumn('id').withTitle('ID'),
        DTColumnBuilder.newColumn('title').withTitle('Title'),
        DTColumnBuilder.newColumn('popularity').withTitle('Popularity')
    ];
    

    vm.dtOptions = DTOptionsBuilder.fromFnPromise(
    	apiService.api("/barrerad/helper.php", {GetData: 1})
    )
    .withPaginationType('full_numbers')
    .withOption('order', [[2, 'desc']]);
});
