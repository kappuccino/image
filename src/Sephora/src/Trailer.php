<?php

namespace YoVideo;

use YoVideo\Exception;

class Trailer{

	private $url;
	private $prefix;

	public function __construct($url){
		$config = Tools::arrayFlatten(Config::get());

		$this->url = $url;
		$this->prefix = 'https://s3-'.$config['aws.region'].'.amazonaws.com/'.$config['aws.s3.bucket'];

		return $this;
	}

	public function url(){
		return $this->prefix.$this->url;
	}


}