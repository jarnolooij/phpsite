<?php

namespace models;

use \Database;

class Model {

    public $id;
	protected $table;
    private static $tableName = null;

	public function __construct() {
        if ($this->table == null) {
            $table = get_called_class();
            $table = explode('\\', $table);
            $table = array_pop($table);
            $table = strtolower($table);

            $this->table = $table;
        }
	}

	public static function all() {
        $table = self::getTable();
		$res = Database::query("SELECT * FROM $table")->get_result();

        $result = array();

        while ($row = $res->fetch_assoc()) {
            $class = get_called_class();
            $instance = new $class;
            
            foreach ($row as $key => $value) {
                $instance->{$key} = $value;
            }
            array_push($result, $instance);
        }

        return $result;
	}

    public static function where($search, $value) {
        $table = self::getTable();
		$res = Database::query("SELECT * FROM $table WHERE $search = ?", $value)->get_result();
        $result = array();

        while ($row = $res->fetch_assoc()) {
            $class = get_called_class();
            $instance = new $class;
            
            foreach ($row as $key => $value) {
                $instance->{$key} = $value;
            }
            array_push($result, $instance);
        }

        return $result;
    }

    public function save() {
        $table = self::getTable();

        $refObject = new \ReflectionObject($this);
        $refProperties = $refObject->getProperties(\ReflectionProperty::IS_PUBLIC);

        if ($this->id == null) {
            $query = "INSERT INTO $table (";
            
            $properties = array();

            foreach ($refProperties as $property) {
                if ($property->name === 'id') {
                    continue;
                }

                if ($property->getValue($this) == null) {
                    throw new \Exception("Property $property->name is null!");
                }

                $query .= "$property->name, ";
                array_push($properties, $property->getValue($this));
            }

            $query = substr($query, 0, -2);
            $query .= ') VALUES (';

            foreach ($properties as $property) {
                $query .= "?, ";
            }

            $query = substr($query, 0, -2);
            $query .= ')';

            Database::query($query, ...$properties);
        } else {
            $query = "UPDATE $table SET ";
        
            $properties = array();
    
            foreach ($refProperties as $property) {
                if ($property->name === 'id') {
                    continue;
                }

                if ($property->getValue($this) == null) {
                    throw new \Exception("Property $property->name is null!");
                }

                if ($property == null) {
                    throw new \Exception("Property $property->name is null!");
                }
    
                $query .= $property->name . ' = ?, ';
                array_push($properties, $property->getValue($this));
            }
            array_push($properties, $this->id);
    
            $query = substr($query, 0, -2);
            $query .= ' WHERE id = ?';

            Database::query($query, ...$properties);
        }
    }

    private static function getTable() {
        if (self::$tableName == null) {
            $class = get_called_class();
            $instance = new $class;
            self::$tableName = $instance->table;
        }

        return self::$tableName;
    }
}