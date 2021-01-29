<?php
class RK {
	const DIRSEP = DIRECTORY_SEPARATOR;
	const NAMESEP = '/';
	protected static $_self;
	// protected $_data;
	public $data;
	public $config;
	public $router;
	public $request;
	public $db;
	
	public function __construct($path_base=null) {
		RK::$_self = $this;
		$this->data = new Engine\Registry;
		
		$this->config = new Engine\Registry;
		$this->config->path = new Module\Path;
		$this->path->base   = (!is_null($path_base)) ? $path_base : dirname($_SERVER['SCRIPT_NAME']);
		$this->path->core   = __DIR__;
		$this->path->config = $this->config->path->core . 'config';
		$this->config->set($this->getConfig('default.php'));
		
		$this->data->set($this->getConfig('info.ini'));
		
		$this->request = $this->getModule('request');
		
		$this->router = new Engine\Router;
		
		ob_start();
	}
	
	public static function self() { return RK::$_self; }
	
	
	public function __get($key) {
		foreach (array('data', 'config') as $var) {
			if ($this->$var->has($key)) return $this->$var->get($key);
		}
	}
	
	public function __set($key, $value) {
		return $this->data->set($key, $value);
	}
	
	public function __isset($key) {
		return $this->data->has($key);
	}
	
	public function __toString() {
		return $this->title . ' v' . $this->version;
	}
	
	
	public function getConfig($filename, $assoc=true) {
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		$config_file = $this->path->getFilename('config', $filename);
		if (empty($config_file)) {
			trigger_error('Config file not found "'. $filename .'"', E_USER_WARNING);
			return array();
		}
		$_ = array();
		switch($ext) {
			case 'php':
				$rk = $this;
				$return = include($config_file);
				if (is_array($return)) $_ = $return;
				break;
			case 'ini':
				$_ = parse_ini_file($config_file, true);
				break;
			case 'json':
				$_ = json_decode($config_file, true);
				break;
		}
		return $_;
	}
	
	
	public function connectDB($config=null) {
		if (empty($config)) $config = $this->config->db;
		extract($config);
		$this->db = new MySQLi($host, $login, $password, $database);
		if ($this->db->connect_errno) {
			trigger_error('MySQL connect failed: ['.$this->db->connect_errno.'] '.$this->db->connect_error, E_USER_WARNING);
			return false;
		}
		$this->db->set_charset($charset);
		return true;
	}
	
	
	// getController
	public function run($action, $data=array()) {
		$parts = explode('/', $action);
		$method = 'action' . ucfirst(array_pop($parts));
		$name = implode('\\', $parts);
		$class = 'Controller\\' . $name;
		$filename = $this->path->getFilename('controller', $name . $this->config->ext_class);
		if (!file_exists($filename)) {
			if ($method == 'actionIndex') trigger_error("Action \"$action\" not found in $filename", E_USER_ERROR);
			$action .= '/index';
			return $this->run($action, $data);
		}
		require_once($filename);
		$controller = new $class;
		return $controller->$method($data);
	}
	
	
	public function getModel($name) {
		$rk_class = get_called_class();
		$file = $this->path->getFilename('model', $name . $this->config->ext_class);
		$class = 'Model\\' . str_replace($rk_class::NAMESEP, '\\', $name);
		if (!file_exists($file)) {
			trigger_error("Model $name not found in \"$file\" ($class)", E_USER_WARNING);
			return new Engine\Model;
		}
		require_once($file);
		return new $class;
	}
	
	
	public function getModule($name) {
		$rk_class = get_called_class();
		$file = $this->path->getFilename('module', $name . $this->config->ext_class);
		$class = 'Module\\' . str_replace($rk_class::NAMESEP, '\\', $name);
		if (!file_exists($file)) {
			trigger_error("Module $name not found in \"$file\" ($class)", E_USER_WARNING);
			return;
		}
		require_once($file);
		return new $class;
	}
	
	
	public function invoke($name) {
		$rk_class = get_called_class();
		$namesep = $rk_class::NAMESEP;
		$dirsep  = $rk_class::DIRSEP;
		$namespace = str_replace($namesep, '\\', $name) . '\\';
		$dir = $this->config->path['core'] . str_replace($namesep, $dirsep, $name) . $dirsep;
		$mFile = $dir . 'model' . $this->config->ext_class;
		$vFile = $dir . 'view' . $this->config->ext_class;
		$cFile = $dir . 'controller' . $this->config->ext_class;
		if (file_exists($mFile)) {
			require_once($mFile);
			$model_class = $namespace . 'Model';
			$model = new $model_class;
		} else {
			$model = new Engine\Model;
		}
		if (file_exists($cFile)) {
			require_once($cFile);
			$controller_class = $namespace . 'Controller';
			$controller = new $controller_class($model);
		} else {
			trigger_error("Invoke <b>$name</b> failed ($cFile)", E_USER_ERROR);
		}
		return $controller;
	}
	
	
	public function output() {
		return ob_get_clean();
	}
	
}