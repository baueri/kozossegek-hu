<?php

namespace App\Models;

use Framework\Model\Model;

/**
 * Description of User
 *
 * @author ivan
 */
class User extends Model
{
    public $id;

    public $name;

    public $username;

    public $password;

    public $email;
}
