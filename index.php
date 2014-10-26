<?php

	require __DIR__.'/bootstrap.php';

?><!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php include __DIR__.'/includes/html.head.php'; ?>
</head>
<body>

<?php include __DIR__.'/includes/left.php'; ?>



<div id="right"><?php

	if(isset($_GET['photographers'])){
		include __DIR__.'/includes/photographers.php';
	}else
	if(isset($_GET['rights'])){
		include __DIR__.'/includes/rights.php';
	}else{
		echo 'pas encore fait';
	}


?></div>


</body>
</html>