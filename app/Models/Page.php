<?php


namespace App\Models;


use Framework\Model\Model;
use Framework\Model\TimeStamps;

class Page extends Model
{
    use TimeStamps;
    
    public $id;

    public $title;

    public $content;

    public $user_id;

    public $status;

    public $slug;
    
    /**
     * @var User
     */
    public $user;

}