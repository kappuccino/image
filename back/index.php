<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script src="../bower_components/angular/angular.min.js"></script>
	<script src="../bower_components/angular-resource/angular-resource.min.js"></script>
	<script src="../bower_components/angular-ui-router/release/angular-ui-router.min.js"></script>
	<script src="../bower_components/lodash/dist/lodash.compat.min.js"></script>
	<script src="../bower_components/angular-local-storage/dist/angular-local-storage.min.js"></script>
	<script src="../bower_components/checklist-model/checklist-model.js"></script>

	<link rel='stylesheet' href='css/style.css' type='text/css' media='all' />
</head>
<body ng-app="sephoraApp" ng-cloak>

<div ui-view="app">

	<nav class="navbar navbar-default" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" ui-sref="home">Sephora</a>
			</div>
			<ul class="nav navbar-nav">
				<li ui-sref-active="active"><a ui-sref="browser({folder:''})">Fichiers</a></li>
			</ul>
		</div>
	</nav>

	<div ui-view="main"></div>

</div>



<!-- | DEPENDENCIES | -->
<script src="../bower_components/jquery/dist/jquery.min.js"></script>
<script src="../bower_components/bootstrap/js/tab.js"></script>

<!-- | APP | -->
<script src="src/app.js"></script>

<!-- | BROWSER | -->
<script src="src/browser/module.js"></script>
<script src="src/browser/route.js"></script>
<script src="src/browser/service/browser.js"></script>
<script src="src/browser/controller/list.js"></script>
<script src="src/browser/controller/selector.js"></script>
<script src="src/browser/directive/path.js"></script>
<script src="src/browser/directive/browser-folder.js"></script>

</html>