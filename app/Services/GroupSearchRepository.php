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

        $varos = mb_strtolower($filter['varos'] ?? '');
        if ($varos) {
            if ($varos === 'budapest') {
                $builder->where('city', 'like', "{$varos}%");
            } else {
                $builder->where('city', 'like', $varos);
            }
        }

        $korosztaly = $filter['korosztaly'];
        if ($korosztaly) {
            $builder->apply('whereAgeGroup', $korosztaly);
        }

        $rendszeresseg = $filter['rendszeresseg'];
        if ($rendszeresseg) {
            $builder->where('occasion_frequency', $rendszeresseg);
        }

        $intezmeny = $filter['institute_id'];
        if ($intezmeny) {
            $builder->where('institute_id', $intezmeny);
        }

        if ($filter->exists('pending')) {
            $builder->where('pending', $filter['pending']);
        }

        $status = $filter['status'];
        if ($status) {
            $builder->where('status', $status);
        }

        $tags = $filter['tags'];
        if ($tags) {
            $builder->apply('whereGroupTag', explode(',', $tags));
        }

        $institute_name = $filter['intezmeny'];
        if ($institute_name) {
            $builder->where('institute_name', 'like', "%$institute_name%");
        }

        $userId = $filter['user_id'];
        if ($userId) {
            $builder->where('user_id', $userId);
        }

        if ($filter['deleted']) {
            $builder->trashed();
        } else {
            $builder->notDeleted();
        }

        $spiritualMovementID = $filter['spiritual_movement_id'];
        if ($spiritualMovementID) {
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
