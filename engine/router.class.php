<?php
namespace Engine;


class Router extends Model {
	
	public function route($routes) {
		$uri = trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
		if ($uri==='') $uri = '/'; // FIX start page url
			foreach($routes as $route) {
			$regexp = $route['rule'];
			$action = $route['action'];
			$result = preg_match('@^'.$regexp.'$@i', $uri, $matches);
			if ($result===1) {
				list($action, $vars) = array_pad(explode('(', trim($action, ')')), 2, '');
				$data = array();
				$vars = explode(',', 'uri,'.$vars);
				foreach($matches as $i => $match) {
					if(array_key_exists($i, $vars)) {
						$data[$vars[$i]] = $match;
					} else {
						$data[$i] = $match;
					}
				}
				echo $this->rk->run($action, $data);
				return true;
			}
		}
		return false;
	}
	
	
	public function routeFile($file) {
		$config = $this->rk->getConfig($file);
		$routes = array();
		foreach($config as $rule => $action) {
			if (is_string($action)) {
				$routes[] = compact('rule', 'action');
			}
			else {
				$routes = $config;
				break;
			}
		}
		return $this->route($routes);
	}
	
	
	public function routeDB() {
		$routes = $this->select('SELECT rule, action FROM rk_routes ORDER BY priority ASC');
		return $this->route($routes);
	}
	
}