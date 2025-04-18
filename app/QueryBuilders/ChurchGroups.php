<?php

namespace App\QueryBuilders;

use App\Enums\GroupStatus;
use App\Enums\GroupPending;
use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use App\Models\GroupTag;
use Framework\Database\Builder;
use App\Models\User;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

/**
 * @phpstan-extends EntityQueryBuilder<ChurchGroup>
 */
class ChurchGroups extends EntityQueryBuilder
{
    use SoftDeletes;

    public const GROUP_SEND_NOTIFICATION_AFTER = '12 MONTH';

    public const GROUP_INACTIVATE_AFTER_NOTIFICATION = '1 MONTH';

    public function tags(): Relation
    {
        return $this->has(Has::many, entity_builder(GroupTag::class), 'group_id');
    }

    public function manager(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }

    public function comment(): Relation
    {
        return $this->has(Has::one, GroupComments::class, 'group_id');
    }

    public function spiritualMovement(): Relation
    {
        return $this->has(Has::one, SpiritualMovements::class, 'id', 'spiritual_movement_id');
    }

    public function active(): static
    {
        return $this->where('pending', GroupPending::confirmed)
            ->where('status', GroupStatus::active)
            ->notDeleted();
    }

    public function bySlug(string $slug): static
    {
        $id = substr($slug, strrpos($slug, '-') + 1);

        return $this->wherePK($id)->active();
    }

    public function whereGroupTag(array $tags): static
    {
        return $this->whereHas('tags', fn (Builder $query) => $query->whereIn('tag', $tags));
    }

    public function similarTo(ChurchGroupView $group): static
    {
        $this->where('id', '<>', $group->getId())
            ->where('city', $group->city)
            ->active();

        if ($group->tags) {
            $tagQuery = fn (EntityQueryBuilder $query) => $query->whereIn('tag', $group->tags->map->tag);
            $this->with('tags', $tagQuery);
            $this->whereHas('tags', $tagQuery);
        }

        return $this;
    }

    public function of(User $user): static
    {
        return $this->where('user_id', $user->getId());
    }

    public function shouldNotify(): static
    {
        return $this
            ->active()
            ->whereRaw(sprintf('(notified_at IS NULL AND DATE(confirmed_at) < DATE_SUB(NOW(), INTERVAL %s))', self::GROUP_SEND_NOTIFICATION_AFTER));
    }

    public function shouldInactivate(): static
    {
        return $this->active()
            ->whereRaw(sprintf('(DATE(notified_at) < DATE_SUB(NOW(), INTERVAL %s))', self::GROUP_INACTIVATE_AFTER_NOTIFICATION));
    }
}
