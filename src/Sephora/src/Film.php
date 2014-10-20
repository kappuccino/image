<?php

namespace YoVideo;

class Film extends Model{

	public function  __construct($data = array()){

		/*$this->setModel([
			'hello'  => 'String',
			'number' => 'Integer',
		#	'prop'   => ['k' => 'String', 'v' => 'String'],
			'stars'  => [['type' => '\YoVideo\Star']]
		]);*/

		if(!empty($data)) $this->set($data);

		parent::__construct();
	}

	public function search(Array $post){

		$url  = '/film';
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
				$tmp = new Film($e);
				$tmp->starMapping();

				$data[$n] = $tmp;
			}
		}

		$this->set($data);

		return $this;
	}

	public function getById($id){

		$url  = '/film/'.$id;

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		$this->set($data);
		$this->starMapping();

		return $this;
	}

	/**
	 * Transforme une liste de stars (actor, director...) en une liste d'Oobjet \YoVideo\Star
	 *
	 */
	private function starMapping(){
		foreach(YoVideo::getPeople() as $field){

			$stars = $this->get($field);
			if(!empty($stars)){
				$list = [];
				foreach($stars as $e){
					$list[] = new Star($e);
				}
				$this->set($field, $list, false);
			}

		}

	}

	public function tournage($id, $star=NULL){

		$film = $this->getById($id);

		$media = $film->get('media');
		if(empty($media)) return [];

		$media = array_filter($media, function($e) use ($star){
			if($star){
				$stars = $e['stars'] ?: [];

				if(!empty($stars)){
					$stars = array_map(function($e){
						return $e['_id'];
					}, $stars);
				}

				return $e['onset'] && in_array($star, $stars);
			}
			return $v = $e['onset'];
		});


		$media = array_values($media);
		usort($media, function($a, $b){
			if($a['url'] > $b['url']) return 1;
			if($a['url'] > $b['url']) return -1;
			return 0;
		});

		return $media;
	}

	public function stats($type){

		$url  = '/film/'.$this->getId().'/stats/'.$type;

		try{
			$data = $this->request->get($url);
		} catch(ApiException $e){
			throw $e;
		}

		return $data;
	}

// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function displayTitle(){
		return $this->get('title');
	}

	/**
	 * Retourne l'URL complète pour le film
	 *
	 * @param boolean full
	 *
	 * @return string
	 */
	public function permalink($full=false){
		$url = '/front/film/detail.php?_id='.$this->getId();
		if($full) $url = 'http://'.$_SERVER['HTTP_HOST'].$url;
		return $url;
	}

	public function htmlLink($label=NULL, $url=NULL, $opt=[]){

		if(empty($label)) $label = $this->get('title');

		$opt = array_merge($opt, [
				'title' => $this->displayTitle()
		]);

		parent::htmlLink($label, $url ?: $this->permalink(true), $opt);
	}

	public function jaquetteURL($size='small'){

		$default   = '/front/asset/_generique.jpg';
		$thumbnail = NULL;
		$media     = $this->get('media');

		$media = array_filter($media, function($e){
			if($e['poster'] && $e['main']) return $e;
		});

		// Remet l'Array dans le bon ordre
		$media = array_values($media);

		if(empty($media)) return $default;
		$media = $media[0];

		$url = new Media('/'.$media['url']);
		$url = $url->params(['height' => 400])->url();

		return $url;
	}

	public function imageUrl($image){
		$default = '/front/asset/_generique.jpg';
		if(!$image OR !is_array($image)) return $default;
		if(!$image['url']) return $default;
		return $image['url'];
	}

	/**
	 * Est-ce que le film a une bannde annonce (trailer principal)
	 *
	 * @return bool
	 */
	public function hasTrailer(){
		$url = $this->trailerUrl();
		return !empty($url);
	}

	public function hasOnset(){
		$media = $this->get('media');
		if(empty($media)) return false;

		$media = array_filter($media, function($medium){
			return $medium['onset'];
		});

		return !empty($media);
	}

	/**
	 * Rertourne l'URL de la bande annonce pour ce film (trailer principal)
	 *
	 * @return bool|string|Media
	 */
	public function trailerUrl(){

		$media = $this->get('media');

		$media = array_filter($media, function($e){
			if($e['trailer'] && $e['main']) return $e;
		});

		// Remet l'Array dans le bon ordre
		$media = array_values($media);

		if(empty($media)) return false;
		$media = $media[0];

		$url = new Trailer('/'.$media['url']);

		return $url->url();
	}

}