<?php

namespace App\Repositories;

use App\Models\Group;
use Framework\Database\PaginatedResultSet;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\ModelNotFoundException;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;
use Framework\Support\Collection;

class GroupRepository extends Repository
{

    public static function getModelClass(): string
    {
        return Group::class;
    }

    public static function getTable(): string
    {
        return 'groups';
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
     * @return PaginatedResultSet|Model[]|ModelCollection|PaginatedModelCollection
     */
    public function search($filter = [], $perPage = 30)
    {
        $builder = $this->getBuilder();

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
        
        if ($filter['deleted']) {
            $builder->whereNotNull('deleted_at');
        } else {
            $builder->whereNull('deleted_at');
        }

        $builder->orderBy($filter['order_by'] ?: 'name', $filter['order'] ?: 'asc');

        $result = $builder->paginate($perPage);

        return $this->getInstances($result);
    }

    /**
     * @param string $slug
     * @return Group
     */
    public function findBySlug($slug)
    {
        $id = substr($slug, strrpos($slug, '-')+1);

        $group = $this->find($id);

        if (!$group) {
            throw new ModelNotFoundException('', 404);
        }

        return $group;
    }

}
