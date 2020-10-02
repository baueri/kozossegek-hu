<?php

namespace App\Models;

use Framework\Model\Model;
use Framework\Model\TimeStamps;

/**
 * Description of User
 *
 * @author ivan
 */
class User extends Model
{
    use TimeStamps;

    public $id;

    public $name;

    public $username;

    public $password;

    public $email;

    public function keresztnev()
    {
        return substr($this->name, strpos($this->name, ' '));
    }

   /**
    * @return bool
    * @todo !!!
    */
    public function isAdmin()
    {
        return true;
    }
}
