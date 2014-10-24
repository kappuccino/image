Editor.config(function ($stateProvider, $urlRouterProvider) {

	$stateProvider

		.state('editor', {
			url: '/editor/*file',
			views: {
				main: {
					templateUrl: 'src/editor/partials/edit.html'
				}
			}
		})

})
