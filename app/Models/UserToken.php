<?php


namespace App\Models;


use Framework\Model\Model;

class UserToken extends Model
{
    public $id;

    public $token;

    public $email;

    public $page;

    public $expires_at;

    public function getUrl()
    {
        return config('app.site_url') . "{$this->page}?token={$this->token}";
    }
}