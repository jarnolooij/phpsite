<?php

require 'inc.php';

use models\User;
use models\TestModel;

Router::get('/', function() {
	View::make('home');
});

Router::get('/upload', function() {	
	View::make('upload');
});

Router::submit();
