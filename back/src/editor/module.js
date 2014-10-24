var Editor = angular.module(
	'Editor',
	['ngResource']
);



Editor.config(['markdownConverterProvider', function (markdownConverterProvider) {
	// options to be passed to Showdown
	// see: https://github.com/coreyti/showdown#extensions
	markdownConverterProvider.config({
	//extensions: ['twitter']
	});
}])