var sephoraApp = angular.module('sephoraApp', [
	'ui.router',
	'blueimp.fileupload', 'ng-context-menu', 'btford.markdown',
	'Browser', 'Editor'
]);

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


// http://stackoverflow.com/questions/14833326/how-to-set-focus-on-input-field
sephoraApp.directive('focusMe', function($timeout, $parse) {
	return {
		//scope: true,   // optionally create a child scope
		link: function(scope, element, attrs) {
			var model = $parse(attrs.focusMe);
			scope.$watch(model, function(value) {
				//console.log('value=',value);
				if(value === true) {
					$timeout(function() {
						element[0].focus();
					});
				}
			});

			// to address @blesh's comment, set attribute value to 'false'
			// on blur event:
			element.bind('blur', function() {
				//console.log('blur');
				scope.$apply(model.assign(scope, false));
			});
		}
	};
});


/*


Element.prototype.remove = function() {
	this.parentElement.removeChild(this);
}

NodeList.prototype.remove = HTMLCollection.prototype.remove = function() {
	for(var i = 0, len = this.length; i < len; i++) {
		if(this[i] && this[i].parentElement) {
			this[i].parentElement.removeChild(this[i]);
		}
	}
}*/
