<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Enums\PageStatus;
use App\Enums\PageType;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

class Pages extends EntityQueryBuilder
{
    use SoftDeletes;

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }

    public function seenAnnouncements(): Relation
    {
        return $this->has(Has::many, builder('seen_announcements'), 'announcement_id', 'id');
    }

    public function whereSlug(string $slug): self
    {
        return $this->where('slug', $slug);
    }

    public function published(): self
    {
        return $this->where('status', PageStatus::PUBLISHED);
    }

    public function announcements(): self
    {
        return $this->where('page_type', 'announcement');
    }

    public function pages(): self
    {
        return $this->where('page_type', 'page');
    }

    public function news(): self
    {
        return $this->where('page_type', PageType::blog);
    }
}
