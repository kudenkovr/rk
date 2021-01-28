<?php
namespace Module;


class Image {
	protected $thumb_dir;
	
	
	public function __construct($image) {
		$rk = RK::self();
		$this->thumb_dir = $rk->path->normalize($rk->path->base . $dirname($image) . '/thumb');
		
	}
	
	public function crop($width, $height) {
		
		return $this;
	}
	
	public function resize($width, $height) {
		
		return $this;
	}
	
	public function save() {
		if (!is_dir($this->thumb_dir)) mkdir($this->thumb_dir, 755);
		
		return
	}
	
	public function thumb($width, $height) {
		
	}
	
}