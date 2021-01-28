<?php
namespace Controller;
use Engine\Controller;


class Index extends Controller {
	
	public function __construct() {
		parent::__construct();
		$this->view->setLayout('layout');
	}
	
	public function actionIndex($data) {
		$this->view->setTemplate('index');
		return $this->view->render();
	}
	
	public function actionError404() {
		header("HTTP/1.0 404 Not Found");
		$this->view->setTemplate('error404');
		return $this->view->render();
	}
	
}