<?php

namespace YoVideo;

class User extends Model{

	private $exists = false;

	public function  __construct($data=NULL){

		if(!empty($data)) $this->set($data);

		parent::__construct();
	}

	public function login($login, $passwd){

		$url  = '/user/login';
		$post = ['login' => $login, 'passwd' => $passwd];

		try{
			$data = $this->request->post($url, $post);
		} catch(Exception $e){
			throw $e;
		}

		if($data['user'] && $data['auth']){
			$_SESSION['yo']['user'] = $data['user']['_id'];
			$_SESSION['yo']['auth'] = $data['auth']['_id'];
		}

		return $this;
	}

	public function logout(){
		unset($_SESSION['yo']['user'], $_SESSION['yo']['auth']);
		return $this;
	}

	public function create($post){

		$url  = '/user';

		try{
			$data = $this->request->put($url, $post);
		} catch(Exception $e){
			if($e->getName() == 'Exists'){
				$this->exists = true;
			}else{
				throw $e;
			}
		}

		return $this;
	}

	public function getConnected(){

		$url  = '/user/'.$this->getUserId();

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);

		return $this;

	}

	public function changePasswd($passwd){

		$auth = $this->getAuthId();

		if(empty($auth)){
			throw new Exception('Try to update the password but auth is unknown');
		}

		$url  = '/auth/'.$auth.'/passwd';
		$post = ['passwd' => $passwd];

		try{
			$data = $this->request->post($url, $post);
		} catch(Exception $e){
			throw $e;
		}

		return $data;
	}

// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function exists(){
		if($this->exists) return $this->exists;
	}

	function isLogged(){
		return !empty($_SESSION['yo']['user']) && !empty($_SESSION['yo']['auth']);
	}

	function getUserId(){
		return $_SESSION['yo']['user'];
	}

	function getAuthId(){
		return $_SESSION['yo']['auth'];
	}

}