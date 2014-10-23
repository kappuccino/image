<?php

	include __DIR__.'/../bootstrap.php';

	$e = new \Sephora\Browser(dirname(__DIR__).'/data/__kill-me');

	$e->remove();