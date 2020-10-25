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

    public $last_login;
    
    public $user_group;

    public function keresztnev()
    {
        return substr($this->name, strpos($this->name, ' '));
    }

   /**
    * @return bool
    */
    public function isAdmin()
    {
        return $this->hasUserGroup('SUPER_ADMIN');
    }
    
    public function hasUserGroup($group)
    {
        return $this->user_group == $group;
    }
}
