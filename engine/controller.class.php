<?php
namespace Engine;
use RK;
use stdClass;


class Controller {
	public $rk;
	public $model;
	public $view;
	
	public function __construct($model=null, $view=null) {
		$this->rk = RK::self();
		$this->_initModel($model);
		$this->_initView($view);
	}
	
	
	protected function _initModel($model) {
		if (is_null($model)) {
			$model_class = str_replace('Controller', 'Model', get_called_class());
			$model_file = $this->rk->path->model
				. str_replace('\\', RK::DIRSEP, strtolower(str_replace('Model\\', '', $model_class)))
				. $this->rk->config->ext_class;
			if (file_exists($model_file)) {
				require_once($model_file);
				$this->model = new $model_class;
			} else {
				$this->model = new Model;
			}
		}
		elseif (is_string($model)) {
			$this->model = $this->rk->getModel($model);
		}
		elseif (is_array($model)) {
			$this->model = new Model;
			$this->model->set($model);
		}
		elseif (is_object($model)) {
			$this->model = $model;
		}
	}
	
	
	protected function _initView($view) {
		if (is_null($view)) {
			$this->view = new View($this->model);
		}
		elseif (is_string($view)) {
			// $this->view = $this->rk->getClass($view);
		}
		elseif (is_array($view)) {
			// 
		}
		elseif (is_object($view)) {
			$this->view = $view;
		}
	}
	
	
	public function actionIndex($data) {}
	
	
	public function __get($key) { return $this->model->get($key); }
	public function __set($key, $value) { return $this->model->set($key, $value); }
	public function __isset($key) { return $this->model->has($key); }
	
}