<?php

use Phalcon\Mvc\Model;

class Users extends Model
{
    public $user_id;
    public $username;
    public $fullname;
    public $email;
    public $password;
    public $role;
    public $status;
    public $phone;
    public $address;
    public $state;
    public $country;
    public $reg_date;
}

