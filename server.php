<?php

require 'inc.php';

use models\User;
use models\Upload;

Router::get('/', function() {
	View::make('home');
});

Router::get('/upload', function() {	
	View::make('upload');
});

Router::post('/register', function() {
    if (isset($_POST['email']) && !empty($_POST["email"]) && (isset($_POST['fotoboek']) && !empty($_POST["fotoboek"]))) {
        if (User::where('email', $_POST["email"]) == null && User::where('fotoboek', $_POST["fotoboek"]) == null) {
            $user = new User;
            $user->email = $_POST['email'];
            $user->fotoboek = $_POST['fotoboek'];
            ///
            $user->save();
        }
    }

    header('location: /upload');
});

Router::post('/upload', function() {
    $upload = new Upload;
    $upload->file = "";
    $upload->description = $_POST["description"];
    $upload->save();

    header('location: /');
});

Router::submit();
