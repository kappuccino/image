<?php

namespace Sephora;

class Browser{

	private $root;
	private $item;
	private $content = [];

	public function  __construct($item = ''){
		$config = Config::get();
		$this->root = $config['root'];
		if($item){
			if(substr($item, 0, 1) != '/')  $item = $this->root.'/'.$item;  // Si pas de / au début => full
			if(substr($item, -1) == '/')    $item = substr($item, 0, -1);   // Kill last /
			$this->item = $item;
		}
		return $this;
	}

	public function getItem($root=true){
		return ($root) ? $this->item : str_replace($this->root.'/', '', $this->item);
	}

	public function isFolder(){
		return is_dir($this->getItem());
	}

	public function isFile(){
		return is_file($this->getItem());
	}

	public function exists(){
		return file_exists($this->getItem());
	}

	public function getContent(){
		return $this->content;
	}

	public function readFolder($folder=NULL){
		$dir = $folder ?: $this->item;
		if(strpos($dir, $this->root) === false) $dir = $this->root.$dir;
		$content = $this->read($dir, NULL, 'FLAT_NOHIDDEN');
		if($content) $this->content = $content;
	}

	public function getMeta($item){
		$item = $item ?: $this->item;
		$file = $item.'.json';

		if(file_exists($file)){
			$raw  = file_get_contents($file);
			$json = json_decode($raw, true);
			return $json;
		}

		return [];
	}

	public function setMeta($meta){
		$file = $this->root.$this->item.'.json';

		$meta = [
			'info'  => $meta['info'],
			'droit' => $meta['droit']
		];

		$json = json_encode($meta);

		file_put_contents($file, $json);

		return $meta;
	}

	public function folder($url=NULL){
		$url = $url ?: $this->item;

		$me = [
			'isFolder' => true,
			'url'      => str_replace($this->root.'/', '', $url),
			'dir'      => $url
		];

		return array_merge($me, $this->getMeta($url));
	}

	public function file($url=NULL){

		$url = $url ?: $this->item;
		$ext = pathinfo($url, PATHINFO_EXTENSION);


		$me = [
			'isFile'     => true,
			'isEditable' => in_array($ext, ['txt']),
			'url'        => str_replace($this->root . '/', '', $url),
			'dir'        => $url,
			'size'       => filesize($url)
		];

		return array_merge($me, $this->getMeta($url));
	}

	public function mkdir($url){

		$path = $this->root.'/'.$this->getItem(false);
		if(substr($path, -1) == '/') $path = substr($path, 0, -1);

		$url  = $path.'/'.$url;

		if(file_exists($url)) throw new Exception('Already exists');

		mkdir($url);

		return $this->folder($url);
	}

	public function move($to){

		$dst = $this->root.'/';
		if(!empty($to)) $dst .= (substr($to, -1) == '/') ? $to : $to.'/';
		$to = $dst . basename($this->item);

		$meta = $this->item.'.json';
		$metaTo = $to.'.json';

	#	echo $this->getItem().' >>> '.$to;

		if($this->item == $to OR file_exists($to) OR file_exists($metaTo)) return false;

		# Item
		#echo $this->item.' > '.$to.PHP_EOL;
		rename($this->item, $to);

		# Meta
		#echo $meta.' > '.$to;
		if(file_exists($meta)) rename($meta, $metaTo);

		return true;
	}

	public function remove(){

		$item = $this->getItem();

	#	echo "Remove: ".$item."<br>";

		if(!file_exists($item)) return false;

		if(is_file($item)){
			$this->removeFile($item);
		}else
		if(is_dir($item)){
			$this->removeFolder($item);
		}

		return true;
	}

	public function rename($newname){

		$final = dirname($this->getItem()).'/'.$newname;
		if(file_exists($final)) return false;

		if(rename($this->getItem(), $final)){
			$this->item = $final;
		}
	}

	public function markdown($url){

		$path = $this->root.'/'.$this->getItem(false);
		if(substr($path, -1) == '/') $path = substr($path, 0, -1);

		$url  = $path.'/'.$url;

		if(file_exists($url)) throw new Exception('Already exists');

		file_put_contents($url, '');

		return $this->file($url);
	}

	public function filterFolder(){

		$content = array_filter($this->getContent(), function($e){
			return $e['isFolder'];
		});

		$this->content = $content;
	}

	public function exclude($items){

		$content = array_filter($this->getContent(), function($e) use ($items){
		#	echo '-'.basename($e['url']).'<br>';
			return !in_array(basename($e['url']), $items);
		});

		$this->content = $content;
	}

// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	private function read($folder, $mask=NULL, $options=NULL, $recursive=false){

		if(substr($folder, -1) == '/') $folder = substr($folder, 0, -1);

		if(!file_exists($folder)){
			throw new Exception('Folder does not exists: '.$folder);
		}

		static $myContent;
		static $myPWD;

		# Recursive
		if(!$recursive){
			$myContent 	= array();
			$myPWD 		= getcwd();
		}

		$denyExt = ['json'];

		# Options
		$flat = false;
		$noHidden = false;
		$usePreg = false;

		$segs = explode('_', $options);
		if(in_array('FLAT', 	$segs)) $flat 		= true;
		if(in_array('NOHIDDEN', $segs)) $noHidden	= true;
		if(in_array('PREG', 	$segs)) $usePreg	= true;


		$dh = opendir($folder);
		$items = [];
		while(false !== ($filename = readdir($dh))){
			if($filename != '.' && $filename != '..'){
				if($noHidden){
					if(substr($filename, 0, 1) != '.') $items[] = $filename;
				}else{
					$items[] = $filename;
				}
			}
		}


		if($mask == NULL){
			foreach($items as $item){
				if(is_dir($folder.'/'.$item)){
					$myContent[] = $this->folder($folder.'/'.$item);
					if(!$flat) $this->read($folder.'/'.$item, $mask, $options, true);
				}else
				if(is_file($folder.'/'.$item)){
					$ext = pathinfo($item, PATHINFO_EXTENSION);
				#	echo $ext.PHP_EOL;
					if(!in_array($ext, $denyExt)) $myContent[] = $this->file($folder.'/'.$item);
				}
			}
		}else{
			chdir($folder);
			$globFiles = glob($mask);
			if(!is_array($globFiles)) $globFiles = array();

			foreach($globFiles as $item){;
				if(is_dir($folder.'/'.$item)){
					$myContent[] = $this->folder($folder.'/'.$item);
					if(!$flat) $this->read($folder.'/'.$item, $mask, $options, true);
				}else
				if(is_file($folder.'/'.$item)){
					$ext = pathinfo($item, PATHINFO_EXTENSION);
					if(!in_array($ext, $denyExt)){
						if($usePreg && preg_match($mask, $item)){
							$myContent[] = $this->file($folder.'/'.$item);
						}else
						if(fnmatch($mask, $item, FNM_CASEFOLD)) {
							$myContent[] = $this->file($folder.'/'.$item);
						}
					}
				}
			}
		}

		chdir($myPWD);

		return $myContent;
	}

	private function removeFile($url){
		if(file_exists($url))           unlink($url);
		if(file_exists($url.'.json'))   unlink($url.'.json');

		return true;
	}

	private function removeFolder($url){

		// Supprimer tous les fichiers
		$contents = $this->read($url) ?: [];
		foreach($contents as $e){
			if($e['isFile']) unlink($e['dir']);
		}

		// Supprimer iterativement tous les dossiers
		if(is_dir($url)) {
			$this->removeFolderLoop($url);
			rmdir($url);
		}
	}

	private function removeFolderLoop($url){

		$contents = $this->read($url, NULL, 'FLAT') ?: [];
		$contents = array_map(function($e){
			if($e['isFolder']) return $e['dir'];
		}, $contents);

		if(!empty($contents)){
			foreach($contents as $e){
				if($e['isFolder']){
					$this->removeFolderLoop($e);
					rmdir($e);
				}
			}
		}

	}

}