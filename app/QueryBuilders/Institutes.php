<?php

namespace App\QueryBuilders;

use App\Models\Institute;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\Model\SoftDeletes;

/**
 * @phpstan-extends EntityQueryBuilder<Institute>
 */
class Institutes extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;
    use SoftDeletes;

    public function cityModel(): Relation
    {
        return $this->has(Has::one, Cities::class, 'name', 'city');
    }

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }

    public function churchGroups(): Relation
    {
        return $this->has(Has::many, ChurchGroups::class);
    }

    public function searchByCityAndInstituteName(string $city, string $institute): self
    {
        return $this->whereRaw("MATCH(city) AGAINST(? IN BOOLEAN MODE)", [$city])
            ->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$institute]);
    }

    public function active(): self
    {
        return $this->notDeleted()->where('approved', '1');
    }

    public function search(?string $keyword): self
    {
        if (!$keyword) {
            return $this;
        }

        $keyword = trim($keyword, ' -*()');
        return $this->whereRaw(
            'MATCH (name, name2, city, district) AGAINST (? IN BOOLEAN MODE)',
            [$keyword ? '+' . str_replace(' ', '* +', $keyword) . '*' : '']
        );
    }
}
