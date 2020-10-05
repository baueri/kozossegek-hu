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
        $builder = builder()->select('*')->from('v_groups');

        if ($keyword = $filter['search']) {
            $builder->where('name', 'like', "%$keyword%");
        }

        if ($varos = $filter['varos']) {
            $builder->where('city', $varos);
        }

        if ($korosztaly = $filter['korosztaly']) {
            $builder->where('age_group', $korosztaly);
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

            $innerQuery = builder('group_tags')->select('group_id')->whereIn('tag', $tags)->toSql();

            $builder->whereRaw("id in ($innerQuery)", $tags);
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
}
