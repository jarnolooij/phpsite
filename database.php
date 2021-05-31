<?php

class Database {
	private static $mysqli = null;

	public static function getConnection() {
        if (self::$mysqli == null) {
            self::$mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);

            if (self::$mysqli->connect_errno) {
                 die("Failed to connect to MySQL: " . self::$mysqli -> connect_error);
            }
    
            self::$mysqli->query("CREATE TABLE IF NOT EXISTS users (id int not null auto_increment, username varchar(25) not null, password varchar(128) not null, PRIMARY KEY(id))");
            self::$mysqli->query("CREATE TABLE IF NOT EXISTS auth_tokens (id integer(12) not null auto_increment, selector char(12), token char(64), user_id integer(11) not null, expires datetime, PRIMARY KEY(id))");
        }

		return self::$mysqli;
	}

    public static function query($query, ...$data) {
        $stmt = self::getConnection()->prepare($query);
        self::dynamicBind($stmt, $data);
        $stmt->execute();
        return $stmt;
    }

    private static function dynamicBind($stmt, $params) {
        if (is_array($params) && $params != null) {
            $types = '';

            foreach($params as $param) {
                $types .= self::getTypes($param);
            }

            $bindNames[] = $types;

            for ($i = 0; $i < count($params); $i++) {
                $bindName = 'bind' . $i;
                $$bindName = $params[$i];
                $bindNames[] = &$$bindName;
            }
            
            call_user_func_array(array($stmt, 'bind_param'), $bindNames);
        } 

        return $stmt;
    }

    private static function getTypes($item){
        switch (gettype($item)) {
            case 'NULL':
            case 'string':
                return 's';
                break;
            case 'integer':
                return 'i';
                break;
            case 'blob':
                return 'b';
                break;
            case 'double':
                return 'd';
                break;
        }

        return '';
    }
}