<?php

namespace App\Repositories;

use Framework\Repository;

use App\Models\GroupView;

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
     * @return PaginatedResultSet|[]
     */
    public function search($filter = [], $perPage = 30)
    {
        if (is_array($filter)) {
            $filter = collect($filter);
        }
        $builder = builder()->select('*')->from('v_groups');

        if ($keyword = $filter['search']) {
            $builder->where('name', 'like', "%$keyword%");
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

        if ($status = $filter['status']) {
            $builder->where('status', $status);
        }

        if ($tags = $filter['tags']) {

            $tags = explode(',', $tags);

            $builder->whereGroupTag($tags);
        }

        if ($filter['deleted']) {
            $builder->whereNotNull('deleted_at');
        } else {
            $builder->whereNull('deleted_at');
        }

        $builder->orderBy($filter['order_by'] ?: 'name', $filter['order'] ?: 'asc');

        return $this->getInstances($builder->paginate($perPage));
    }

    /**
     * @param string $slug
     * @return Group
     */
    public function findBySlug($slug)
    {
        $id = substr($slug, strrpos($slug, '-')+1);

        $group = $this->find($id);

        return $group;
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

        return $this->getInstances($builder->get());
    }
}
