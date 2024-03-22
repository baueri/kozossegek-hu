<?php

declare(strict_types=1);

namespace App\Models;

use App\Auth\Auth;
use App\Enums\PageType;
use App\QueryBuilders\Pages;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\EntitySiteMappable;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Model\SoftDeletes;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

/**
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $user_id
 * @property string $status
 * @property null|string $header_image
 * @property string $priority
 * @property string $page_type
 * @property ?User $user
 * @property Collection $seenAnnouncements
 */
class Page extends Entity
{
    use SoftDeletes;
    use HasTimestamps;
    use EntitySiteMappable;

    public function getUrl(): string
    {
        if ($this->page_type === PageType::blog->value()) {
            return route('portal.blog.view', $this->slug);
        }
        return route('portal.page', $this->slug);
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
}
