<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use App\Models\User;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

/**
 * @phpstan-extends EntityQueryBuilder<\App\Models\ChurchGroup>
 */
class ChurchGroups extends EntityQueryBuilder
{
    use SoftDeletes;

    public static function getModelClass(): string
    {
        return ChurchGroup::class;
    }

    public function tags(): Relation
    {
        return $this->has(Has::many, GroupTags::class);
    }

    public function manager(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }

    public function active(): static
    {
        return $this->where('pending', 0)
            ->where('status', 'active')
            ->notDeleted();
    }

    public function bySlug(string $slug): static
    {
        $id = substr($slug, strrpos($slug, '-') + 1);

        return $this->wherePK($id)->active();
    }

    public function whereGroupTag(array $tags): static
    {
        $innerQuery = builder('group_tags')->distinct()->select('group_id')->whereIn('tag', $tags);
        $this->whereRaw("id in ($innerQuery)", $tags);
        return $this;
    }

    public function similarTo(ChurchGroupView $group): static
    {
        $this->where('id', '<>', $group->getId())
            ->where('city', $group->city)
            ->active();

        if ($group->tags) {
            $tagQuery = fn (GroupTags $query) => $query->whereIn('tag', $group->tags->map->tag);
            $this->with('tags', $tagQuery);
            $this->whereHas('tags', $tagQuery);
        }

        return $this;
    }

    public function of(User $user): static
    {
        return $this->where('user_id', $user->getId());
    }

    public function editableBy(User $user): static
    {
        return $this->whereExists(
            builder('managed_church_groups')
                ->whereRaw("group_id={$this->getTable()}.id")
                ->where('user_id', $user->getId())
        , null, 'or');
    }
}
