<?php

namespace App\Repositories;

use App\Models\Group;
use App\Models\User;
use Framework\Database\PaginatedResultSet;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;
use App\Models\GroupView;
use Framework\Support\Collection;

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
     * @param int $perPage
     * @return PaginatedModelCollection
     */
    public function all($perPage = 30)
    {
        $result = $this->getBuilder()->paginate($perPage);

        return $this->getInstances($result);
    }

    /**
     * @param Collection|array $filter
     * @param int $perPage
     * @return PaginatedResultSet|Model[]|ModelCollection|PaginatedModelCollection []
     */
    public function search($filter = [], $perPage = 30)
    {
        if (is_array($filter)) {
            $filter = collect($filter);
        }

        $builder = builder()->select('*')->from('v_groups');

        if ($keyword = $filter['search']) {
            $keywords = '+'.str_replace(' ', '* +', trim($keyword, ' ')) . '*';
            /* @var $found PaginatedResultSet */

            $found = db()->select('select group_id
                from search_engine 
                where MATCH(keywords) AGAINST (? IN BOOLEAN MODE)', [$keywords]);

            if ($found) {
                $builder->whereIn('id', collect($found)->pluck('group_id')->all());
            } else {
                $builder->where('name', 'like', "%$keyword%");
            }
        }

        if ($varos = $filter['varos']) {
            $builder->where('city', $varos);
        }

        if ($korosztaly = $filter['korosztaly']) {
            $builder->whereAgeGroup($korosztaly);
            // $builder->whereInSet('age_group', $korosztaly);
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

        if ($filter['deleted']) {
            $builder->deleted();
        } else {
            $builder->notDeleted();
        }

        $builder->orderBy($filter['order_by'] ?: 'name', $filter['order'] ?: 'asc');

        return $this->getInstances($builder->paginate($perPage));
    }

    /**
     * @param string $slug
     * @return GroupView
     */
    public function findBySlug($slug)
    {
        $id = substr($slug, strrpos($slug, '-')+1);

        $builder = $this->getBuilder();

        $row = $builder->where('id', $id)->notDeleted()->first();

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
            ->limit($take);

        if ($tags) {
            $builder->whereGroupTag(collect($tags)->pluck('tag')->all());
        }

        $groups = $this->getInstances($builder->get());

        $group_tags = builder('group_tags')->whereIn('group_id', $groups->pluck('id')->toArray())->get();
        

        return $groups;
    }

    public function getGroupsWithoutUser()
    {
        $builder = $this->getBuilder()
            ->where('user_id', '0')
            ->where('group_leaders', '<>', '')
            ->where('group_leader_email', '<>', '')
            ->apply('notDeleted');

        return $this->getInstances($builder->get());
    }

    public function getNotDeletedGroupsByUser($user)
    {
        return $this->getInstances(
            $row = $this->getBuilder()->where('user_id', $user->id)->apply('notDeleted')->get()
        );
    }
}
