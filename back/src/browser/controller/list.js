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

		$scope.$emit('browser.folder', '');

		BrowserService.query({folder: $scope.folder}, function(data){
			$scope.items = data;
			$scope.$emit('browser.folder', $scope.folder);
		});
	}

	$rootScope.$on('uploader.done', function(evt, d){
		var data = {
			isFile: true,
			url: $scope.folder + d.name,
			size: d.size
		}

		console.log(data, d)

		$scope.items.push(data)
	});


	$scope.save = function(me) {

		me.folder = $scope.folder
		var item = new BrowserService(me);

		item.$save(function (data) {
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

	$scope.remove = function(selection){

		selection = selection || $scope.selection;

		if(confirm("Confirmez vous la suppression de ces elements ?")) {

			var item = new BrowserService({
				folder: $scope.folder,
				items: selection
			});

			item.$remove(function(){
				for(var i=0; i<selection.length; i++){
					for(var j=0; j<$scope.items.length; j++){
						if(selection[i] == $scope.items[j]['url']) $scope.items.splice(j,1);
					}
				}
			});
		}

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