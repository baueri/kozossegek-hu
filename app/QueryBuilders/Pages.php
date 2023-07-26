<?php

namespace App\QueryBuilders;

use App\Enums\PageStatus;
use App\Models\Page;
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

    public function whereSlug(string $slug): self
    {
        return $this->where('slug', $slug);
    }

    public function published(): self
    {
        return $this->where('status', PageStatus::PUBLISHED);
    }
}