Browser.controller('BrowserSelectorCtrl', function($scope, $rootScope){

	var panel = document.getElementById('selector')

	$scope.selection = '';

	$rootScope.$on('selector.open', function(){
		$scope.show()
	});

	$rootScope.$on('selector.hide', function(){
		$scope.hide()
	});




	$scope.hide = function(){
		panel.classList.add('hide')
	};

	$scope.show = function(){
		panel.classList.remove('hide')
	};

	$scope.confirm = function(){
		$rootScope.$emit('selector.picked', $scope.selection);
	};

});