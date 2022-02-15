<?php

namespace App\Models;

use App\Auth\Auth;
use Framework\Model\Model;
use Framework\Model\TimeStamps;
use Framework\Support\StringHelper;

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

    public ?User $user = null;

    public function getUrl(): string
    {
        return config('app.site_url') . '/' . $this->slug;
    }

    public function excerpt(int $numberOfWords = 20): string
    {
        return StringHelper::more($this->content, $numberOfWords);
    }

    public function pageTitle(): string
    {
        $link = '';
        if (Auth::loggedIn() && Auth::user()->isAdmin()) {
            $route = route('admin.page.edit', $this);
            $link = "<a href='{$route}' target='_blank' title='Szerkesztés' class='edit-page'><i class='fa fa-pencil-alt'></i></a>";
        }

        return "{$this->title} {$link}";
    }
}
