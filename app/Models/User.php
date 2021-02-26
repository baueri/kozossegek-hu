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

    public $activated_at;

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

    public function firstName()
    {
        return substr($this->name, strpos($this->name, ' '));
    }

    public function isActive()
    {
        return $this->activated_at !== '0000-00-00 00:00:00' && !is_null($this->activated_at);
    }
}
