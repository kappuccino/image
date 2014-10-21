Browser.controller('BrowserListCtrl', function (
	$scope, $rootScope, $state, $stateParams, $filter,
	localStorageService,
	BrowserService
){

	//if($state.current.name != 'film') return;

	$scope.folder = $stateParams.folder || '';
	$scope.items = [];

	loadView();

	function loadView(){
		$scope.selection = [];

		BrowserService.query({folder: $scope.folder}, function(data){
			$scope.items = data;
		});
	}


	$scope.save = function(me) {

		me.folder = $scope.folder
		var item = new BrowserService(me);

		item.$save(function (data) {
			var panel = document.getElementById('selector')

			me = data
		});
	};

	$scope.move = function(){
		$scope.$emit('selector.open', true);

		$rootScope.$on('selector.picked', function(e, folder){

			var item = new BrowserService({
					folder: $scope.folder,
					dst: folder,
					src: $scope.selection
				});

			item.$move(function(){
				$scope.$emit('selector.hide', true);
				loadView();
			});

		});

	};

	$scope.mkdir = function(){
		var dir = prompt("Nouveau dossier")
			, item = new BrowserService({
				folder: $scope.folder,
				dir: dir
			});

		if(!dir) return false;

		item.$mkdir(function (data){
			$scope.items.push(data);
		});
	};

});