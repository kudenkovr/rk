<?php
namespace Controller;
use Engine\Controller;


class Index extends Controller {
	
	public function actionIndex($data) {
		return $this->view->render('index', 'layout');
	}
	
	public function actionError404() {
		header("HTTP/1.0 404 Not Found");
		return $this->view->render('error404', 'layout');
	}
	
}