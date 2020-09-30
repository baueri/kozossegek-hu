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

    public function keresztnev()
    {
        return substr($this->name, strpos($this->name, ' '));
    }
}
