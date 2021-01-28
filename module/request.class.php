<?php
namespace Module;
use Engine\Registry;


class Request extends Registry {
	
	public function __construct() {
		$this->set($_REQUEST);
		$this->get = $_GET;
		$this->post = $_POST;
	}
	
	
	public function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
						&& !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
						&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}
	
}