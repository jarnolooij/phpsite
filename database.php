<?php

class Database {
	private $mysqli;

	public function __construct() {
		// Initialize database
		$this->mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);

		if ($this->mysqli->connect_errno) {
		 	die("Failed to connect to MySQL: " . $this->mysqli -> connect_error);
		}

		// Set up table if required
		$this->mysqli->query("CREATE TABLE IF NOT EXISTS users (id int not null auto_increment, username varchar(25) not null, password varchar(128) not null, PRIMARY KEY(id))");
		$this->mysqli->query("CREATE TABLE IF NOT EXISTS auth_tokens (id integer(12) not null auto_increment, selector char(12), token char(64), user_id integer(11) not null, expires datetime, PRIMARY KEY(id))");
	}

	public function getMysql() {
		return $this->mysqli;
	}

	public function query($query, $types, ...$data) {
		$stmt = $this->getMysql()->prepare($query);
		$stmt->bind_param($types, ...$data);
		$stmt->execute();
		
		return $stmt;
	}
}