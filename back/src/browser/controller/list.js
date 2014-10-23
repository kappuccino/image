Browser.controller('BrowserListCtrl', function (
	$scope, $rootScope, $state, $stateParams, $filter,
	localStorageService,
	BrowserService
){

	if($state.current.name != 'browser') return;

	$scope.folder = $stateParams.folder || '';
	$scope.items = [];

	function loadView(){

		if($scope.folder)
		$scope.selection = [];

		$scope.$emit('browser.folder', '');

		BrowserService.query({folder: $scope.folder}, function(data){
			$scope.items = data;
			$scope.$emit('browser.folder', $scope.folder);
		});
	}

	loadView();

	$rootScope.$on('uploader.done', function(evt, d){

		var dir = $scope.folder ? $scope.folder+'/' : '/'
			, data = {
					isFile: true,
					url: dir + d.name,
					size: d.size
				};

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

	$scope.remove = function(){

		var selection = $scope.selection;

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

			$scope.selection = [];
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