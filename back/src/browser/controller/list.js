Browser.controller('BrowserListCtrl', function (
	$scope, $rootScope, $state, $stateParams, $filter,
	localStorageService,
	BrowserService, ContextMenuService
){

	if($state.current.name != 'browser') return;

	$scope.folder = $stateParams.folder || '';
	$scope.items = [];

	function loadView(){

		$scope.selection = [];

		$scope.$emit('browser.folder', '');

		BrowserService.query({folder: $scope.folder}, function(data){
			$scope.items = data;
			$scope.$emit('browser.folder', $scope.folder);
		});
	}

	loadView();

	// Quand une instance d'upload a terminé
	$rootScope.$on('uploader.done', function(evt, d){

		var dir = $scope.folder ? $scope.folder+'/' : ''
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


		if(confirm("Confirmez vous la suppression de ces elements ?")) {

			var item = new BrowserService({
				folder: $scope.folder,
				items: $scope.selection
			});

			item.$remove(function(){
				console.log($scope.selection)
				for(var sel=0; sel<$scope.selection.length; sel++){
					for(var i=0; i<$scope.items.length; i++){
						if($scope.selection[sel] == $scope.items[i]['url']) $scope.items.splice(i, 1);
					}
				}

				$scope.selection = [];
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

	$scope.newTxt = function(){
		var file = prompt("Nouveau fichier texte")
			, item = new BrowserService({
					folder: $scope.folder,
					file: file
				});

		if(!file) return false;

		item.$markdown(function (data){
			$scope.items.push(data);
		});

	}

	$scope.rename = function(me){

		var item = new BrowserService({
					folder: $scope.folder,
					item: me.url,
					newname: me.newname
				});

		if(!me.newname || me.newname == $filter('basename')(me.url)) return false;

		item.$rename(function(data){
			me.url = data.url;
			me.dir = data.dir;

			delete me.renaming;
			delete me.newname;

			console.log(data)
		});
	};

	$scope.menuRename = function(){
		var item = ContextMenuService.data;
		item.newname  = $filter('basename')(item.url);
		item.renaming = true;
	}

	$scope.menuIsEditable = function(){
		var item = ContextMenuService.data;
		return item ? item.isEditable : false;
	}

	$scope.menuGetItemUrl = function(){
		var item = ContextMenuService.data;
		return item.url;
	}

});

