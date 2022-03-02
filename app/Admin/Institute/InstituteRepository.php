<?php

namespace App\Admin\Institute;

use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use Framework\Model\PaginatedModelCollection;

class InstituteRepository
{
    public function __construct(
        private readonly Institutes $repository
    ) {
    }

    public function getInstitutes($filter = []): PaginatedModelCollection
    {
        $builder = $this->repository->whereNull('institutes.deleted_at');

        if ($city = $filter['city']) {
            $builder->addSelect('institutes.*')->where('city', $city);
        }

        if ($name = $filter['search']) {
            $matchAgainst = trim($name, ' -*()');
            $builder->whereRaw(
                'MATCH (name, name2, city, district) AGAINST (? IN BOOLEAN MODE)',
                [$matchAgainst ? '+' . str_replace(' ', '* +', $matchAgainst) . '*' : '']
            );
        }

        if ($filter['sort']) {
            $orderBy = $filter['order_by'] ?: $builder::primaryCol();
            if ($orderBy != 'groups_count') {
                $builder->orderBy($orderBy, $filter['sort']);
            }
        } else {
            $builder->orderBy('institutes.id', 'desc');
        }
        $builder->withCount('groups', fn (ChurchGroups $groups) => $groups->where('pending', 0)->notDeleted());
        return $builder->paginate(30);
    }
}
