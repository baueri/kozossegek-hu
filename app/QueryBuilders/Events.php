<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\Event;
use App\Models\EventTag;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

use function Symfony\Component\String\b;

/**
 * @extends EntityQueryBuilder<Event>
 */
class Events extends EntityQueryBuilder
{
    protected string $table = 'events';

    /* ---------------------------
     * RELATIONS
     * ---------------------------*/

    public function tags(): Relation
    {
        return $this->has(Has::many, builder('event_tags'), 'event_id');
    }

    public function church(): Relation
    {
        return $this->has(Has::one, Institutes::class, 'id', 'church_id');
    }

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }

    /* ---------------------------
     * SCOPES
     * ---------------------------*/

    public function approved(): static
    {
        return $this->where('status', 'approved');
    }

    public function active(): static
    {
        return $this->where('lifecycle', 'active');
    }

    public function cancelled(): static
    {
        return $this->where('lifecycle', 'cancelled');
    }

    public function upcoming(): static
    {
        return $this->where('starts_at', '>=', date('Y-m-d H:i:s'));
    }

    public function between($from, $to): static
    {
        return $this->where('starts_at', '>=', $from)
            ->where('starts_at', '<=', $to);
    }

    public function atChurch(int $churchId): static
    {
        return $this->where('church_id', $churchId);
    }

    public function whereTag(array $tags): static
    {
        return $this->whereHas(
            'tags',
            fn($q) =>
            $q->whereIn('tag', $tags)
        );
    }

    public function bySlug(string $slug): static
    {
        return $this->where('slug', $slug);
    }
}
