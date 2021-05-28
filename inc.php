<?php

require 'envloader.php';
require 'database.php';
require 'router.php';
require 'auth.php';
require 'view.php';

function dd(...$obj) {
	var_dump($obj);
	die();
}


function flash($key) {
	if (isset($_SESSION['flash'][$key])) {
		$msg = $_SESSION['flash'][$key]; 
		unset($_SESSION['flash'][$key]);
		return $msg;
	}
}