<?php
namespace Engine;
use RK;


class Model extends Registry {
	protected $rk;
	protected $db;
	
	public function __construct() {
		$this->rk = RK::self();
		$this->db =& $this->rk->db;
		$this->init();
	}
	
	
	public function init() {}
	
	
	public function select($query) {
		$result = $this->db->query($query);
		if ($this->db->errno) {
			trigger_error($this->db->error, E_USER_WARNING);
			return array();
		}
		$array = $result->fetch_all(MYSQLI_ASSOC);
		return $array;
	}
	
}