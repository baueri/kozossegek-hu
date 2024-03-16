<?php

declare(strict_types=1);

namespace App\Models;

use App\Auth\Auth;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\EntitySiteMappable;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Model\SoftDeletes;
use Framework\Support\StringHelper;

/**
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $user_id
 * @property string $status
 * @property null|string $header_image
 * @property string $priority
 * @property ?User $user
 */
class Page extends Entity
{
    use SoftDeletes;
    use HasTimestamps;
    use EntitySiteMappable;

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

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::yearly;
    }

    public function toAnnouncement(): string
    {
        $image = $this->header_image ? "<img src='{$this->header_image}' alt='{$this->title}' class='img-fluid mb-2' style='height: 230px; width: 100%; object-fit: cover;'>" : '';
        $content = str_replace('{{ $user }}', Auth::user()->name, $this->content);

        return <<<HTML
        <div class='announcement'>
            {$image}
            <h3 class='announcement-header text-left text-sm-center my-3'>
                $this->title
            </h3>
            <div class='announcement-content'>$content</div>
        </div>
        HTML;
    }
}
