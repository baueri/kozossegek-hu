<?php


namespace App\Models;


use Framework\Model\Model;

class Page extends Model
{
    use \Framework\Model\TimeStamps;
    
    public $id;

    public $title;

    public $content;

    public $user_id;

    public $status;

    public $slug;

}