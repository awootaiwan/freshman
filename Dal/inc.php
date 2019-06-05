<?php
function dalAutoLoader($class) {
	$rlt = false; 

	$namespace = 'Dal\\';
	if (strpos($class, $namespace) === 0) {
		$filePath = str_replace('\\', '/', $class) . '.php';

		if (is_file($filePath)) {
			include_once $filePath;
			$rlt = class_exists($class, false);
		}
	}

	return $rlt;
}

spl_autoload_register('dalAutoLoader');
