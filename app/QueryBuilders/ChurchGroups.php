<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

/**
 * @phpstan-extends EntityQueryBuilder<\App\Models\ChurchGroup>
 */
class ChurchGroups extends EntityQueryBuilder
{
    use SoftDeletes;

    protected static function getModelClass(): string
    {
        return ChurchGroup::class;
    }

    public function tags(): Relation
    {
        return $this->hasMany(GroupTags::class);
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
            $this->withWhereHas('tags', fn (GroupTags $query) => $query->whereIn('tag', $group->tags->map->tag));
        }

        return $this;
    }
}
