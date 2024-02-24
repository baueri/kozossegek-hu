<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\QueryBuilders\ChurchGroupViews;
use Framework\Model\ModelCollection;
use Framework\Support\StringHelper;

class GroupSearchRepository
{
    public function __construct(
        public readonly ChurchGroupViews $repository
    ) {
    }

    public function search($filter = []): ChurchGroupViews
    {
        if (is_array($filter)) {
            $filter = collect($filter);
        }

        $builder = $this->repository;

        $keyword = trim(StringHelper::sanitize(str_replace(['-', '.', '(', ')', '*', '+'], ' ', $filter['search'] ?? '')));

        if ($keyword) {
            $sanitized_keyword = trim(StringHelper::sanitize(str_replace(['-', '.', '(', ')', '*', '+'], ' ', $keyword)));

            $keywords = '+' . str_replace(' ', '* +', trim($sanitized_keyword, ' ')) . '*';

            $builder->whereRaw('id in(select group_id
            from search_engine 
            where MATCH(keywords) AGAINST (? IN BOOLEAN MODE))', $keywords);
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

        $builder->with('tags');

        return $builder;
    }

    public function getNotDeletedGroupsByUser(User $user): ModelCollection
    {
        return $this->repository->query()->forUser($user)->editableBy($user)->notDeleted()->get();
    }
}
