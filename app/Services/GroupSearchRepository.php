<?php

namespace App\Services;

use App\Models\ChurchGroupView;
use App\Models\UserLegacy;
use App\QueryBuilders\GroupViews;
use Framework\Support\StringHelper;

class GroupSearchRepository
{
    public function __construct(private GroupViews $repository)
    {
    }

    public function search($filter = [], ?int $perPage = 30)
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
            $builder->apply('deleted');
        } else {
            $builder->apply('notDeleted');
        }

        $builder->orderBy($filter['order_by'] ?: 'id', $filter['sort'] ?: 'desc');

        if ($perPage == -1) {
            return $builder->get();
        }

        return $builder->paginate($perPage);
    }

    public function findBySlug(string $slug)
    {
        $id = substr($slug, strrpos($slug, '-') + 1);

        $builder = $this->repository->query();

        return $builder->where('id', $id)->apply('notDeleted')->first();
    }

    public function findSimilarGroups(ChurchGroupView $group, $tags, int $take = 4): array|\Framework\Model\ModelCollection
    {
        $builder = $this->repository->query()
            ->where('id', '<>', $group->id)
            ->where('city', $group->city)
            ->whereNull('deleted_at')
            ->where('pending', 0)
            ->where('status', 'active')
            ->limit($take);

        if ($tags) {
            $builder->apply('whereGroupTag', collect($tags)->pluck('tag')->all());
        }

        $groups = $builder->get();

        $groupids = $groups->pluck('id');

        if ($groupids->isNotEmpty()) {
            $group_tags = builder('v_group_tags')
                ->whereIn('group_id', $groupids->all())
                ->get();

            if ($group_tags) {
                $groups->withMany($group_tags, 'tags', 'id', 'group_id');
            }
        }

        return $groups;
    }

    public function getNotDeletedGroupsByUser(UserLegacy $user)
    {
        return $this->repository->query()->forUser($user)->whereNull('deleted_at')->get();
    }
}
