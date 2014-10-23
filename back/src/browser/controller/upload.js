'use strict';

var isOnGitHub = window.location.hostname === 'blueimp.github.io',
	url = isOnGitHub ? '//jquery-file-upload.appspot.com/' : 'upload/';

sephoraApp.config([
	'$httpProvider', 'fileUploadProvider',
	function ($httpProvider, fileUploadProvider) {

		delete $httpProvider.defaults.headers.common['X-Requested-With'];

		fileUploadProvider.defaults.redirect = window.location.href.replace(
			/\/[^\/]*$/,
			'/cors/result.html?%s'
		);

		if (isOnGitHub) {
			// Demo settings:
			angular.extend(fileUploadProvider.defaults, {
				// Enable image resizing, except for Android and Opera,
				// which actually support image resizing, but fail to
				// send Blob objects via XHR requests:
				disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
				maxFileSize: 5000000,
				acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
			});
		}
	}
]);

sephoraApp.controller('DemoFileUploadController',
	['$scope', '$http', '$rootScope',
	function ($scope, $http, $rootScope) {

		$scope.folder = '';

		$rootScope.$on('browser.folder', function (e, folder) {
			$scope.folder = folder;
		});

		// Quand l'upload d'un fichier est terminé
		$rootScope.$on('fileuploaddone', function (e, file) {

			if(!file.files.length) return

			var myFile = file.files[0];

			for(var i=0; i<$scope.queue.length; i++){
				if($scope.queue[i]['$$hashKey'] == myFile['$$hashKey']){
					$scope.queue.splice(i, 1);
					$rootScope.$emit('uploader.done', myFile)
				}
			}

		});

		function updateFormData(){
			$scope.options = {
				url: url,
				autoUpload: true,
				formData: [
					{name: 'folder', value: $scope.folder}
				]
			};
		}

		updateFormData();
		$scope.$watch('folder', updateFormData)


/*	$scope.loadingFiles = true;

		$http.get(url)
		.then(
			function (response) {
				$scope.loadingFiles = false;
				$scope.queue = response.data.files || [];
			},
			function () {
				$scope.loadingFiles = false;
			});
*/

	}
]);

sephoraApp.controller('FileDestroyController', [
	'$scope', '$http',
	function ($scope, $http) {



		var file = $scope.file,
			state;

		if (file.url) {

			file.$state = function () {
				return state;
			};

			file.$destroy = function () {
				state = 'pending';
				return $http({
					url: file.deleteUrl,
					method: file.deleteType
				}).then(
					function () {
						state = 'resolved';
						$scope.clear(file);
					},
					function () {
						state = 'rejected';
					}
				);
			};

		}else
		if (!file.$cancel && !file._index) {
			file.$cancel = function () {
				$scope.clear(file);
			};
		}






	}
]);

