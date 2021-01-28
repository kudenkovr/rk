<?php
namespace Engine;


class Registry {
	
	public function set($key, $value=null) {
		if (is_null($value) && is_array($key)) {
			foreach($key as $k=>$v) {
				$this->set($k, $v);
			}
		}
		elseif (is_string($key)) {
			if (is_array($value) && $this->has($key) && is_array($this->$key)) {
				// setArray()
				$this->$key = array_replace_recursive($this->$key, $value);
			} elseif (is_array($value) && $this->has($key) && is_object($this->$key)) {
				// setObject()
				$this->$key->set($value);
			} else {
				$this->$key = $value;
			}
		}
	}
	
	
	public function get($key) {
		return $this->$key;
	}
	
	
	public function has($key) {
		return isset($this->$key);
	}
	
	
	public function toArray() {
		return (array) $this;
	}
	
}