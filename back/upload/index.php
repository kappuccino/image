<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

	error_reporting(E_ALL | E_STRICT);

	include __DIR__.'/../../bootstrap.php';
	require('UploadHandler.php');

	$config = Sephora\Config::get();


	$folder    = $_POST['folder'].'/';
	$directory = $config['root'] . '/' . $folder;

	$upload_handler = new UploadHandler(array(
		'upload_dir'                   => $directory,
		'upload_url'                   => $folder,
		'access_control_allow_methods' => array(
			'POST'
		),
		'image_versions'               => array()
	));
