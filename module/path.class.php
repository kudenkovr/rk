<?php
namespace Module;


class Path {
	const DIRSEP = DIRECTORY_SEPARATOR;
	protected $_data = array();
	
	
	public function normalize($path) {
		$path = str_replace('/', self::DIRSEP, $path);
		$path = str_replace('\\', self::DIRSEP, $path);
		$path = rtrim($path, self::DIRSEP);
		if (is_dir($path)) $path .= self::DIRSEP;
		return $path;
	}
	
	
	public function set($key, $value=null) {
		if (is_null($value) && is_array($key)) {
			foreach($key as $k=>$v) {
				$this->set($k, $v);
			}
		} else {
			$path = $this->normalize($value);
			if (!array_key_exists($key, $this->_data)) $this->_data[$key] = array();
			if (is_dir($path)) {
				array_unshift($this->_data[$key], $path);
			} else {
				print_r($value);
				trigger_error("Directory \"$path\" is not exists", E_USER_WARNING);
			}
		}
	}
	
	
	public function &get($key) {
		if (array_key_exists($key, $this->_data)) {
			foreach ($this->_data[$key] as $path) {
				if (is_dir($path)) return $path;
			}
		}
	}
	
	
	public function getFilename($path_key, $file_name) {
		if (array_key_exists($path_key, $this->_data)) {
			foreach ($this->_data[$path_key] as $path) {
				$file_path = $path . $file_name;
				if (file_exists($file_path)) {
					return $this->normalize($file_path);
				}
			}
			// from $path->base:
			$file_path = $this->get('base') . $file_name;
			if (file_exists($file_path)) {
				return $this->normalize($file_path);
			}
		}
		return false;
	}
	
	
	public function __set($key, $value) { $this->set($key, $value); }
	public function __get($key) { return $this->get($key); }
}