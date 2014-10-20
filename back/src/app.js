var sephoraApp = angular.module('sephoraApp', ['ui.router', 'Browser']);

// http://scotch.io/tutorials/javascript/angular-routing-using-ui-router
sephoraApp.config(function($stateProvider, $urlRouterProvider) {

	$stateProvider

		.state('home', {
			url: '/home',
			views: {
				'main': {
					templateUrl: 'src/home/partials/index.html'
				}
			}
		})

	$urlRouterProvider.otherwise('/home');

});


sephoraApp.filter('basename', function() {
	return function(input) {
		return input.replace(/\\/g,'/').replace( /.*\//, '' );
	};
});

sephoraApp.filter('dirname', function() {
	return function(input) {
		return input.replace(/\\/g,'/').replace(/\/[^\/]*$/, '');;
	};
});

sephoraApp.directive('ngEnter', function () {
	return function (scope, element, attrs) {
		element.bind("keydown keypress", function (event) {
			if(event.which === 13) {
				scope.$apply(function (){
					scope.$eval(attrs.ngEnter);
				});

				event.preventDefault();
			}
		});
	};
});
