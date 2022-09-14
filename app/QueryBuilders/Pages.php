<?php

namespace App\QueryBuilders;

use App\Enums\PageStatus;
use App\Models\Page;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\SoftDeletes;

class Pages extends EntityQueryBuilder
{
    use SoftDeletes;

    public static function getModelClass(): string
    {
        return Page::class;
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