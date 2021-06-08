<?php

namespace App\Repositories;

use App\Models\User;
use Framework\Database\PaginatedResultSet;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;
use App\Models\GroupView;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

class GroupViews extends Repository
{
    public static function getModelClass(): string
    {
        return GroupView::class;
    }

    public static function getTable(): string
    {
        return 'v_groups';
    }

    public function getGroupByUser(User $user)
    {
        $row = $this->getBuilder()->where('user_id', $user->id)->first();

        return $this->getInstance($row);
    }

    /**
     * @param Collection|array $filter
     * @param int|null $perPage
     * @return PaginatedResultSet|Model[]|ModelCollection|PaginatedModelCollection []
     */
    public function search($filter = [], ?int $perPage = 30)
    {
        if (is_array($filter)) {
            $filter = collect($filter);
        }

        $builder = builder()->select('*')->from('v_groups');

        if ($keyword = $filter['search']) {
            $keyword = StringHelper::sanitize(str_replace('-', ' ', $keyword));
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

        if ($varos = $filter['varos']) {
            if ($varos === 'Budapest') {
                $builder->where('city', 'like', "{$varos}%");
            } else {
                $builder->where('city', $varos);
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
            return $this->getInstances($builder->get());
        }

        return $this->getInstances($builder->paginate($perPage));
    }

    /**
     * @param string $slug
     * @return GroupView|null
     */
    public function findBySlug(string $slug)
    {
        $id = substr($slug, strrpos($slug, '-') + 1);

        $builder = $this->getBuilder();

        $row = $builder->where('id', $id)->apply('notDeleted')->first();

        if ($row) {
            return $this->getInstance($row);
        }

        return null;
    }

    public function findSimilarGroups(GroupView $group, $tags, int $take = 4)
    {
        $builder = $this->getBuilder()
            ->where('id', '<>', $group->id)
            ->where('city', $group->city)
            ->whereNull('deleted_at')
            ->where('pending', 0)
            ->where('status', 'active')
            ->limit($take);

        if ($tags) {
            $builder->apply('whereGroupTag', collect($tags)->pluck('tag')->all());
        }

        $groups = $this->getInstances($builder->get());

        $groupids = $groups->pluck('id');

        if ($groupids->isNotEmpty()) {
            $group_tags = builder('v_group_tags')
                ->whereIn('group_id', $groupids->toArray())
                ->get();

            if ($group_tags) {
                $groups->withMany($group_tags, 'tags', 'id', 'group_id');
            }
        }

        return $groups;
    }

    public function getGroupsWithoutUser()
    {
        $builder = $this->getBuilder()
            ->whereRaw('(user_id=0 or user_id is null)')
            ->where('group_leaders', '<>', '')
            ->where('group_leader_email', '<>', '')
            ->apply('notDeleted');

        return $this->getInstances($builder->get());
    }

    public function getNotDeletedGroupsByUser($user)
    {
        return $this->getInstances(
            $this->getBuilder()->where('user_id', $user->id)->apply('notDeleted')->get()
        );
    }
}
