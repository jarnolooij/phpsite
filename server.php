<?php

require 'inc.php';

use models\User;
use models\TestModel;

Router::get('/', function() {
	if (!isset($_SESSION['user'])) {
		header('location: /login');
	} else {
		View::make('index', ['user' => $_SESSION['user']]);
	}
});

Router::get('/login', function() {	
	if (isset($_SESSION['user'])) {
		header('location: /');
	} else {
		View::make('login');
	}
});

Router::get('/register', function() {
	if (isset($_SESSION['user'])) {
		header('location: /');
	} else {
		View::make('register');
	}
});

Router::post('/auth/login', function() {
	$_SESSION['flash']['LOGIN_MESSAGE'] = Auth::instance()->login($_POST['username'], $_POST['password'], isset($_POST['remember']));
	header('location: /login');
});

Router::post('/auth/register', function() {
	$_SESSION['flash']['REG_MESSAGE'] = Auth::instance()->register($_POST['username'], $_POST['password'], $_POST['password_confirm']);
	header('location: /register');
});

Router::get('/session', function() {
	dd($_SESSION);
});

Router::get('/session/destroy', function() {
	session_destroy();
	header('location: /');
});

Router::get('/test/update/:id', function($id) {
	$test = TestModel::where('id', $id)[0];
    $test->name = "RENAME TEST!!";
    $test->save();
});

Router::get('/test/new/:name', function($name) {
    $test = new TestModel;
    $test->name = $name;
    $test->save();
    echo $test->id;
});

Router::get('/test', function() {
	$tests = TestModel::all();
    
    View::make('test', [
        'tests' => $tests
    ]);
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
