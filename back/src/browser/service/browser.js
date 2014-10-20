

//angular.module('starter.services', [])

// http://fdietz.github.io/recipes-with-angular-js/consuming-external-services/consuming-restful-apis.html

Browser.factory('BrowserService', function($resource) {

	return $resource('action.php', {folder: '@dfolder'}, {
		query: {
			params: {'content' : true},
			method:'POST',
			isArray: true
		},

		save: {
			params: {'save' : true},
			method: 'POST'
		},

		mkdir: {
			params: {'mkdir' : true},
			method: 'POST'
		},

		move: {
			params: {'move' : true},
			method: 'POST',
			isArray: true
		}

	});

});