<?php

namespace App\Services;

use App\Models\User;
use App\QueryBuilders\GroupViews;
use Framework\Database\Builder;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\StringHelper;

class GroupSearchRepository
{
    public function __construct(public readonly GroupViews $repository)
    {
    }

    public function search($filter = [], ?int $perPage = 30): PaginatedModelCollection|ModelCollection
    {
        if (is_array($filter)) {
            $filter = collect($filter);
        }

        $builder = $this->repository;

        if ($keyword = $filter['search']) {
            $keyword = StringHelper::sanitize(str_replace(['-', '.', '(', ')'], ' ', $keyword));
            $keywords = '+' . str_replace(' ', '* +', trim($keyword, ' ')) . '*';

            $found = db()->select('select group_id
                from search_engine 
                where MATCH(keywords) AGAINST (? IN BOOLEAN MODE)', [$keywords]);

            if ($found) {
                $builder->whereIn('id', collect($found)->pluck('group_id')->all());
            } else {
                $builder->where('name', 'like', "%{$keyword}%");
            }
        }

        if ($varos = mb_strtolower($filter['varos'] ?? '')) {
            if ($varos === 'budapest') {
                $builder->where('city', 'like', "{$varos}%");
            } else {
                $builder->where('city', 'like', $varos);
            }
        }

        if ($korosztaly = $filter['korosztaly']) {
            $builder->apply('whereAgeGroup', $korosztaly);
        }

        if ($rendszeresseg = $filter['rendszeresseg']) {
            $builder->where('occasion_frequency', $rendszeresseg);
        }

        if ($intezmeny = $filter['institute_id']) {
            $builder->where('institute_id', $intezmeny);
        }

        if ($filter->exists('pending')) {
            $builder->where('pending', $filter['pending']);
        }

        if ($status = $filter['status']) {
            $builder->where('status', $status);
        }

        if ($tags = $filter['tags']) {
            $tags = explode(',', $tags);
            $builder->apply('whereGroupTag', $tags);
        }

        if ($institute_name = $filter['intezmeny']) {
            $builder->where('institute_name', 'like', "%$institute_name%");
        }

        if ($userId = $filter['user_id']) {
            $builder->where('user_id', $userId);
        }

        if ($filter['deleted']) {
            $builder->trashed();
        } else {
            $builder->notDeleted();
        }

        if ($spiritualMovementID = $filter['spiritual_movement_id']) {
            $builder->where('spiritual_movement_id', $spiritualMovementID);
        }

        $builder->orderBy($filter['order_by'] ?: 'id', $filter['sort'] ?: 'desc');

        if ($perPage == -1) {
            return $builder->get();
        }

        return $builder->paginate($perPage);
    }

    public function getNotDeletedGroupsByUser(User $user): ModelCollection
    {
        return $this->repository->query()->forUser($user)->editableBy($user)->notDeleted()->get();
    }
}
