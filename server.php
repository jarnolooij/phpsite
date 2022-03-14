<?php

require 'inc.php';

use models\User;
use models\TestModel;

Router::get('/', function() {
	View::make('index');
});

Router::get('/home', function() {	
	View::make('upload');
});

Router::submit();
