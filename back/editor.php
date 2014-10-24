<?php

	include __DIR__.'/../bootstrap.php';

	$body = file_get_contents('php://input');
	$post = json_decode($body, true);

	$config = Sephora\Config::get();

	if($_GET['read']){
		$Editor = new Sephora\Editor($post['url']);
		$out = $Editor->get();
	}else

	if($_GET['save']){
		$Editor = new Sephora\Editor($post['url']);
		$Editor->updateContent($post['content']);

		$out = $Editor->get();
	}








	if(isset($_GET['debug'])){
		print_r($out);
	}else{
		echo \Sephora\Tools::jsonBeautifier($out);
	}
