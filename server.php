<?php

require 'inc.php';

new EnvLoader;
$database = new Database;
$auth = new Auth($database);

Router::loadDependency('Auth', $auth);
Router::loadDependency('Database', $database);

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

Router::post('/auth/login', function(Auth $auth) {
	$_SESSION['flash']['LOGIN_MESSAGE'] = $auth->login($_POST['username'], $_POST['password'], isset($_POST['remember']));
	header('location: /login');
});

Router::post('/auth/register', function(Auth $auth) {
	$_SESSION['flash']['REG_MESSAGE'] = $auth->register($_POST['username'], $_POST['password'], $_POST['password_confirm']);
	header('location: /register');
});

Router::get('/session', function() {
	dd($_SESSION);
});

Router::get('/session/destroy', function() {
	session_destroy();
	header('location: /');
});

Router::get('/test', function() {
	View::make('test', [
		'test' => 'testing variables',
		'array' => [1, 2, 3, 4]
	]);
});

Router::submit();
