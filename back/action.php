<?php

	include __DIR__.'/../bootstrap.php';

	$body = file_get_contents('php://input');
	$post = json_decode($body, true);

	if(substr($folder, -1) == '/') $folder = substr($folder, 0, -1);




	if($_GET['content']){
		$Browser = new Sephora\Browser($post['folder']);
		$Browser->readFolder();
		$out = $Browser->getContent();
	}else

	if($_GET['save']){
		$Browser = new Sephora\Browser('/'.$post['url']);
		$out = $Browser->setMeta($post);
	}else

	if($_GET['mkdir']){
		$Browser = new Sephora\Browser($post['folder']);
		$out = $Browser->mkdir($post['dir']);
	}else

	if($_GET['move']){
		$out = [];
		foreach($post['src'] as $e){
			$e = new \Sephora\Browser($e);
			$e->move($post['dst']);
		}
	}else

	if($_GET['remove']){
		$out = [];
		foreach($post['items'] as $url){
			$e = new \Sephora\Browser($url);
			$e->remove();
		}
	}








	if(isset($_GET['debug'])){
		print_r($out);
	}else{
		echo \Sephora\Tools::jsonBeautifier($out);
	}
