<?php

$_SERVER['PATH_INFO'] = (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/');

session_start();

require __dir__ . '/../server.php';