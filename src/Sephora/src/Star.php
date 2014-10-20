<?php

namespace YoVideo;

class Star extends Model{

	public function  __construct($data = array()){

		$this->setModel([
			'name'  => 'String',
			'subs' => [['type' => '\YoVideo\Star']]
		]);

		if(!empty($data)) $this->set($data);

		parent::__construct();
	}

	public function getById($id){

		$url  = '/star/'.$id;

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);

		return $this;
	}

	public function getFullById($id){

		$url  = '/star/'.$id.'/full';

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);

		return $this;
	}

	public function search(Array $post){

		$url  = '/star';
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

	public function tournage(){

		$url = '/star/'.$this->getId().'/tournage';

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		if(!empty($data)){
			foreach($data as $n => $e){
				$data[$n] = new Film($e);
			}
		}

		$this->set($data);

		return $this;
	}

	public function stats($type){

		$url  = '/star/'.$this->getId().'/stats/'.$type;

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		return $data;
	}


// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/**
	 * Retourne le nom formaté
	 *
	 * @return array|null|string
	 */
	public function displayName(){
		return $this->get('name');
	}

	/**
	 * Retourne le nom d'un job depuis un champs
	 *
	 * @param $job string Le nom du champs
	 *
	 * @return string
	 */
	public function jobName($job){
		if($job == 'actor')         return 'Acteur';
		if($job == 'director')      return '';
		if($job == 'scriptwriter')  return 'Scénariste';
		if($job == 'music')         return 'Musique non originale';
		if($job == 'scripthelper')  return 'Aide au scénario';
		if($job == 'featuring')     return 'Figurant';
		if($job == 'photography')   return 'Directeur de la photographie';
		if($job == 'author')        return 'Auteur';
	}

	/**
	 * Retourne l'URL complète pour une star
	 *
	 * @param boolean full
	 *
	 * @return string
	 */
	public function permalink($full=false){
		$url = '/front/star/detail.php?_id='.$this->getId();
		if($full) $url = 'http://'.$_SERVER['HTTP_HOST'].$url;
		return $url;
	}

	/**
	 * Retourne un lien <a></a> HTML pointant vers le detail d'une star
	 *
	 * @param null  $label
	 * @param null  $url
	 * @param array $opt
	 *
	 * @return string|void
	 */
	public function htmlLink($label=NULL, $url=NULL, $opt=[]){

		if(empty($label)) $label = $this->get('name');

		$opt = array_merge($opt, [
			'title' => $this->displayName()
		]);

		parent::htmlLink($label, $url ?: $this->permalink(true), $opt);
	}

	/**
	 * Retourne une filmographie classée par date et par job
	 *
	 * @return array
	 */
	public function filmographie(){

		$filmo = [];
		$raw = $this->get('filmo');

		// Order by DATE
		 usort($raw, function($a, $b){
			return $a['film']['date'] > $b['film']['date'];
		});

		// Order by JOB
		foreach($raw as $r){
			if(!array_key_exists($r['job'], $filmo)) $filmo[$r['job']] = [];
			$filmo[$r['job']][] = new Film($r['film']);
		}

		return $filmo;
	}

	public function portraitURL(){
		$default   = '/front/asset/_generique.jpg';
		$thumbnail = NULL;
		$media     = $this->get('media');

		$media = array_filter($media, function($e){
			if($e['poster'] && $e['main']) return $e;
		});

		if(empty($media)) return $default;
		$media = $media[0];

		$url = new Media('/'.$media['url']);
		$url = $url->params(['height' => 400])->url();

		return $url;
	}

}