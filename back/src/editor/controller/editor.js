Editor.controller('EditorCtrl', function (
	$scope, $rootScope, $state, $stateParams, $filter,
	EditorService
){

	if($state.current.name != 'editor') return;

	$scope.file = EditorService.query({url: $stateParams.file}, function(data){
		//$scope.file = data;
	});

	$scope.save = function() {

		$scope.file.$save(function (data) {

		});
	};

	$scope.back = function(){
		var folder = $filter('dirname')('/'+$stateParams.file)
		console.log(folder)
		$state.go('browser', {folder: folder})

	}



});

