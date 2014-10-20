<?php

namespace YoVideo;

use YoVideo\Exception;
use GuzzleHttp\Exception\RequestException;

class Request{

	private $rest;
	private $serverUrl;
	private $version;
	private $auth;

	public function __construct($data = array()){

		$config = Config::get();

		$this->serverUrl = 'http://'.$config['host'];
		if(!empty($config['port'])) $this->serverUrl .= ':'.$config['port'];

		$this->auth    = $config['auth'];
		$this->version = 'v'.$config['version'];
		$this->rest    = $this->rest();

		return $this;
	}

	/**
	 * Recupère une instance du client REST pour intérogger l'API
	 *
	 * @return \GuzzleHttp\Client
	 */
	private function rest(){
		$client = new \GuzzleHttp\Client([
				'base_url' => $this->serverUrl,
				'defaults' => [
					'headers' => [
						'Accept' => 'application/json'
					#	'Authorization' => $this->auth
					]
				]
		]);

		return $client;
	}

	/**
	 * Envois une requete via le Client REST, retourne un ARRAY si le résultat est du JSON si non RAW
	 *
	 * @param string $verb (get, post, put, delete)
	 * @param string $url
	 * @param array  $opt (request options);
	 *
	 * @throws ApiException
	 * @throws \SebastianBergmann\Exporter\Exception
	 *
	 * @return array|\GuzzleHttp\Stream\StreamInterface|null|string
	 *
	 */
	private function request($verb, $url, $opt=array()){

		$url = '/'.$this->version.$url;
		$options = array_merge(['exceptions' => false], $opt);

#		echo '<pre>';
#		echo $verb.' '.$url.PHP_EOL;
#		print_r($options);
#		echo '</pre>';

		#Tools::pre($options);

		#	die();
		#try{
		$data = $this->rest->$verb($url, $options);
		#	Tools::pre('RAW', $data);
		//	var_dump($data);
		#} catch (RequestException $e){
		#	echo '<pre>';
		#	print_r($e);
		#	echo '</pre>';
		#}

		$code = $data->getStatusCode();
		$out  = $data->getBody();

		// JSON ?
		if(strpos($data->getHeader('content-type'), 'application/json') !== false){
			$out = $data->json();
		}

		if($code > 200){

			if(is_array($out) && NULL !== $out['error']['name']){
				/*echo '<pre>JSON de l exception';
				print_r($out);
				echo '</pre>';*/

				throw new Exception($out['error'], $code);
			}

			throw new Exception('Api Exception', $code);
		}

		return $out;
	}

	/**
	 * GET
	 *
	 * @param $url
	 * @param $params
	 *
	 * @return array|\GuzzleHttp\Stream\StreamInterface|null|string
	 */
	public function get($url, $params=NULL, $options=array()){
		if($params) $url .= '?'.http_build_query($params);
		return $this->request('get', $url, $options);
	}

	/**
	 * POST
	 *
	 * @param $url
	 * @param $data
	 *
	 * @return array|\GuzzleHttp\Stream\StreamInterface|null|string
	 */
	public function post($url, Array $data = []){

		#echo $url;
		#var_dump($data);

		return $this->request('post', $url, ['body' => $data]);
	}

	/**
	 * PUT
	 *
	 * @param $url
	 * @param $data
	 *
	 * @return array|\GuzzleHttp\Stream\StreamInterface|null|string
	 */
	public function put($url, Array $data){
		return $this->request('put', $url, ['body' => $data]);
	}

	/**
	 * DELETE
	 *
	 * @param $url
	 * @param $data array of POST value
	 *
	 * @return array|\GuzzleHttp\Stream\StreamInterface|null|string
	 */
	public function delete($url, $data=NULL){
		$opt = [];
		if(!empty($data)) $opt = ['body' => $data];

		return $this->request('delete', $url, $opt);
	}


}