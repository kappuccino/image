Browser.config(function ($stateProvider, $urlRouterProvider) {

	$stateProvider

		.state('browser', {
			url: '/browse/*folder',
			views: {
				main: {
					templateUrl: 'src/browser/partials/index.html'
				},
				'form@browser': {
					templateUrl: 'src/browser/partials/upload-form.html'
				}
			}
		})


})
