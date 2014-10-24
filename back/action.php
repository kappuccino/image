<?php

	include __DIR__.'/../bootstrap.php';

	$body = file_get_contents('php://input');
	$post = json_decode($body, true);

	$config = Sephora\Config::get();

	if(substr($folder, -1) == '/') $folder = substr($folder, 0, -1);

	if($_GET['content']){

		// @ = Roo = Home
		if($post['folder'] == '@'){
			$out = array(
				array(
					'url'      => '/',
					'name'     => 'Home',
					'dir'      => $config['root'].'/',
					'isFolder' => true,
					'isRoot'   => true
				)
			);

		}else{
			$Browser = new Sephora\Browser($post['folder']);
			$Browser->readFolder();
			$out = $Browser->getContent();
		}

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
		$dst = $post['dst'] == '/' ? '' : $post['dst'];
		foreach($post['src'] as $e){
			$e = new \Sephora\Browser($e);
			$e->move($dst);
		}
	}else

	if($_GET['remove']){
		$out = [];
		foreach($post['items'] as $url){
			$e = new \Sephora\Browser($url);
			$e->remove();
		}
	}else

	// Pour le renommage, on ne dispose pas
	if($_GET['rename']){
		$f = $config['root'].'/'.$post['item'];

		$e = new \Sephora\Browser($f);
		$e->rename($post['newname']);

		if($e->isFolder()){
			$out = $e->folder();
		}else
		if($e->isFile()){
			$out = $e->file();
		}
	}

	if($_GET['markdown']){
		$Browser = new Sephora\Browser($post['folder']);
		$out = $Browser->markdown($post['file']);
	}









	if(isset($_GET['debug'])){
		print_r($out);
	}else{
		echo \Sephora\Tools::jsonBeautifier($out);
	}
