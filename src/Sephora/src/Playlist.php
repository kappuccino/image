<?php

namespace YoVideo;

class Playlist extends Model{

	public function  __construct($data = array()){

		if(!empty($data)) $this->set($data);

		parent::__construct();
	}

	public function search(Array $post){

		$url  = '/playlist';
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
				$tmp = new Playlist($e);

				$data[$n] = $tmp;
			}
		}

		$this->set($data);

		return $this;
	}

	public function getById($id){

		$url  = '/playlist/'.$id;

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);
		$this->filmMapping();

		return $this;
	}

	public function getByUser($id=NULL){

		if(empty($id)){
			$user = new User();
			$id = $user->getUserId();
		}

		if(empty($id)){
			throw new Exception('Impossible to get playlist from user with empty `id`');
		}

		$this->search(['_user' => $id, 'auto' => false]);

		return $this->get();
	}

	public function getFilms(){
		$films = $this->get('films');
		if(!$films || empty($films)) $films = [];
		return $films;
	}

	public function create($data){

		$url  = '/playlist';

		// Ajouter le user à la volée
		if(!$data['_user']){
			$user = new User();
			$data['_user'] = $user->getUserId();
		}

		// Lever une exception si on n'a pas pu mettre de User dans cette playlist
		if(!$data['_user']){
			throw new Exception('Try to create a playlist with empty `_user` key');
		}

		try{
			$data = $this->request->put($url, $data);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);

		return $this;
	}

	public function pushFilm($id){

		$pid = $this->getId();

		if(!$pid){
			throw new Exception('Try to push a film to a playlist with empty film `id`');
		}

		$url  = '/playlist/'.$pid.'/push';
		$data = ['film' => $id];

		try{
			$data = $this->request->post($url, $data);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);

		return $this;
	}

	public function pullFilm($id){

		$pid = $this->getId();

		if(!$pid){
			throw new Exception('Try to pull a film from a playlist with empty film `id`');
		}

		$url  = '/playlist/'.$pid.'/pull';
		$data = ['film' => $id];

		try{
			$data = $this->request->post($url, $data);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);

		return $this;
	}

	private function filmMapping(){

		$films = $this->get('films');

		if(!empty($films)){
			$list = [];
			foreach($films as $n => $e){
				$e['film'] = new Film($e['film']);
				$list[] = $e;
			}

			$this->set('films', $list, false);
		}

	}


// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function permalink($full=false){
		$url = '/front/playlist/detail.php?_id='.$this->getId();
		if($full) $url = 'http://'.$_SERVER['HTTP_HOST'].$url;
		return $url;
	}


}