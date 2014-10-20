<?php

namespace YoVideo;

use YoVideo\Exception;

class Media{

	private $url;
	private $params;

	public function __construct($url){
		$config = Config::get();

		$this->url = $url;
		$this->params = [];

		return $this;
	}

	public function params($params){

		$match = [
			'height'  => 'h',
			'width'   => 'w',
			'quality' => 'q',
			'gravity' => 'g'
		];

		foreach($params as $name => $val){
			if(array_key_exists($name, $match)){
				$this->params[] = $match[$name].':'.$val;
			}
		}

		return $this;
	}

	public function url(){
		$params = implode(',', $this->params);
		return 'http://localhost:2000/'.$params.$this->url;
	}


}