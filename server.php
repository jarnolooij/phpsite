<?php

require 'inc.php';

use models\User;
use models\TestModel;

Router::get('/', function() {
	if (!isset($_SESSION['user'])) {
		header('location: /home');
	} else {
		View::make('index', ['user' => $_SESSION['user']]);
	}
});

Router::get('/home', function() {	
	if (isset($_SESSION['user'])) {
		header('location: /');
	} else {
		View::make('home');
	}
});

Router::get('/upload', function() {
	if (isset($_SESSION['user'])) {
		header('location: /');
	} else {
		View::make('upload');
	}
});

Router::post('/auth/home', function() {
	$_SESSION['flash']['LOGIN_MESSAGE'] = Auth::instance()->login($_POST['email']);
	header('location: /home');
});

Router::post('/auth/upload', function() {
	$_SESSION['flash']['REG_MESSAGE'] = Auth::instance()->register($_POST['img'], $_POST['description']);
	header('location: /upload');
});

Router::get('/session', function() {
	dd($_SESSION);
});

Router::get('/session/destroy', function() {
	session_destroy();
	header('location: /');
});

Router::get('/download', function() {
    if (isset($_SESSION['user'])) {
        $path = '/Users/radboudmetselaar/Desktop/logintest/private/private.txt';
    
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($path));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path));
        ob_clean();
        flush();
        readfile($path);
    } else {
        header('location: /');
    }
});

Router::submit();
