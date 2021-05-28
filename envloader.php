<?php

class EnvLoader {
	public function __construct() {
		$file = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES);
		
		foreach ($file as $line) {
			$key = explode("=", $line)[0];
			$value = explode("=", $line)[1];
		
			$_ENV[$key] = $value;
		}
	}
}