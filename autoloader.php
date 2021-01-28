<?php

function autoloader($class) {
	$file = __DIR__ . DIRECTORY_SEPARATOR . strtolower(str_replace('\\', DIRECTORY_SEPARATOR, $class)) . '.class.php';
	if (file_exists($file)) {
		require_once($file);
		return true;
	}
	return false;
}

spl_autoload_register('autoloader');