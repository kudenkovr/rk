<?php
return array(
	'path' => array(
		'controller' => $rk->config->path->core . 'controller',
		'engine'     => $rk->config->path->core . 'engine',
		'model'      => $rk->config->path->core . 'model',
		'module'     => $rk->config->path->core . 'module',
		'template'   => $rk->config->path->core . 'template',
		// 'cache'      => $rk->config->path->core . 'cache',
	),
	
	
	'ext_template' => '.tpl',
	'ext_class' => '.class.php',
	
	'cache_expiried' => 3600,
	'cache_template' => false,
	
	'default_action' => 'index',
	
	'db' => array(
		'host' => '127.0.0.1',
		'login' => null,
		'password' => null,
		'database' => 'rk_light',
		'charset' => 'utf8'
	)
);