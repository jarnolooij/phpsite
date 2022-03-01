<?php

spl_autoload_register(function ($class_name) {
    include str_replace('\\', '/', strtolower($class_name)) . '.php';
});

EnvLoader::load();

function dd(...$obj) {
    foreach($obj as $dump) {
        var_dump($dump);
    }
	die();
}

function flash($key) {
	if (isset($_SESSION['flash'][$key])) {
		$msg = $_SESSION['flash'][$key]; 
		unset($_SESSION['flash'][$key]);
		return $msg;
	}
}
