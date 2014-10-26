<?php

namespace Sephora;

use \Michelf\Markdown;

class Editor {

	private $root;
	private $file;

	public function  __construct($file = '') {
		$config     = Config::get();
		$this->root = $config['root'];

		if ($file) {
			if (substr($file, 0, 1) != '/') $file = $this->root . '/' . $file;      // Si pas de / au début => full
			if (substr($file, -1) == '/') $file = substr($file, 0, -1);           // Kill last /
			$this->file = $file;
		}

		return $this;
	}

	public function getFile() {
		return $this->file;
	}

	public function get() {

		$file = $this->getFile();

		$Browser = new Browser($file);
		$out     = $Browser->file();

		#	$directory = dirname($out['url']);
		#	$out['directory'] = ($directory == '.') ? '' : $directory;

		if (file_exists($file) && is_file($file)) $out['content'] = $this->getContent();

		return $out;
	}

	public function updateContent($content) {
		$file = $this->getFile();
		if (file_exists($file) && is_file($file)) return file_put_contents($file, $content);

		return false;
	}


// HELPERS /////////////////////////////////////////////////////////////////////////////////////////////////////////////

	public function exists() {
		return file_exists($this->getFile());
	}

	public function getContent(){
		$file = $this->getFile();
		if (file_exists($file) && is_file($file)) return file_get_contents($file);
		return false;
	}


	public function markdown(){
		return Markdown::defaultTransform($this->getContent());

	}

}