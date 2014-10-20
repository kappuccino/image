Browser.directive('browserFolder', function(){

	return {
		restrict: 'E',
		templateUrl: 'src/browser/directive/browser-folder.html',

		scope: {
			folder: '@',
			selection: '='
		},

		controller: function($scope, $element, $compile, BrowserService) {

			BrowserService.query({folder: $scope.folder}, function(data){
				$scope.items = data;
			});

			$scope.sel = function(evt, url){
				var item = evt.target;

				if(item.classList.contains('selected')) {
					$scope.selection = '';
				}else{
					$scope.selection = url;
				}

			}

			$scope.$watch('selection', function(n, o){
				var prevSel = document.querySelector('.dirName.selected')
				if(prevSel) prevSel.classList.remove('selected');

				if(!n) return;
				var dirname = document.querySelector('a[url="'+n+'"]').parentNode.querySelector('.dirName');
				if(dirname) dirname.classList.add('selected')
			});

			$scope.toggle = function(ev){

				var browserFolder
					, a = ev.target.parentNode
					, url = a.getAttribute('url')
					, li = a.parentNode
					, child = li.querySelector('.child')
					, ul = child.querySelector('ul');

				if(ul){
					li.classList.toggle('opened');
				}else{
					li.classList.add('opened');
					browserFolder = $compile('<browser-folder folder="'+ url +'" selection="selection"></browser-folder>')($scope);
					angular.element(child).append(browserFolder);
				}

			};

		}
	}

});