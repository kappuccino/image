<?php

namespace YoVideo;

class Search extends Model{

	public function  __construct(){

		$this->request = new Request();

		return $this;
	}

	public function film(Array $post){

		$url  = '/search/film';
		$data = array();

		try{
			$result = $this->request->post($url, $post);
		} catch(Exception $e){
			throw $e;
		}

		$data = $result['data'];
		$this->setTotal(intval($result['total']));

		if(!empty($data)){
			foreach($data as $n => $e){
				$data[$n] = new Film($e);
			}
		}

		$this->set($data);

		return $this;
	}

	public function star(Array $post){

		$url  = '/search/star';
		$data = array();

		try{
			$result = $this->request->post($url, $post);
		} catch(Exception $e){
			throw $e;
		}

		$data = $result['data'];
		$this->setTotal(intval($result['total']));

		if(!empty($data)){
			foreach($data as $n => $e){
				$data[$n] = new Star($e);
			}
		}

		$this->set($data);

		return $this;
	}


}