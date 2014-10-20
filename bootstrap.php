<?php

	ini_set('error_reporting', E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_WARNING);

	require __DIR__.'/vendor/autoload.php';

	Sephora\Config::loadFromFile(__DIR__.'/config.php');
