

//angular.module('starter.services', [])

// http://fdietz.github.io/recipes-with-angular-js/consuming-external-services/consuming-restful-apis.html

Browser.factory('EditorService', function($resource) {

	return $resource('editor.php', {}, {

		'query': {
			params: {'read' : true},
			method:'POST'
		},

		'save': {
			params: {'save' : true},
			method: 'POST'
		}

	});

});