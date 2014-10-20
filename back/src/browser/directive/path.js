Browser.directive('sephoraPath', function(){

	return {
		restrict: 'E',
		templateUrl: 'src/browser/directive/path.html',

		scope: {
			url: '@'
		},

		controller: function($scope, $element) {

			var path = ''
				, url = $scope.url
				, parts = url.split('/')

			$scope.paths = [];

			for(var i=0; i<parts.length; i++){
				if(parts[i]){
					path += parts[i]+'/'

					$scope.paths.push({
						name: parts[i],
						path: path
					});
				}
			}

		}
	}

})
