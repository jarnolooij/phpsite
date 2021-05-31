<?php

namespace models;

use models\Model;

class User extends Model {

    protected $table = 'users';

    public $hidden = ['password'];

    public $username;
    public $password;

}