<?php

namespace App\Models;

use App\Auth\Auth;
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

    public $header_image;

    /**
     * @var User
     */
    public $user;

    public function getUrl()
    {
        return config('app.site_url') . '/' . $this->slug;
    }

    public function pageTitle()
    {
        $link = '';
        if (Auth::loggedIn() && Auth::user()->isAdmin()) {
            $route = route('admin.page.edit', $this);
            $link = "<a href='{$route}' target='_blank' title='Szerkesztés' class='edit-page'><i class='fa fa-pencil-alt'></i></a>";
        }

        return "{$this->title} {$link}";
    }
}
