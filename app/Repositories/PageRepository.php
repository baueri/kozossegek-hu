<?php

namespace App\Repositories;

use App\Models\Page;
use Framework\Database\PaginatedResultSet;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\ModelNotFoundException;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;
use Framework\Support\Collection;

class PageRepository extends Repository
{
    public function findBySlug(string $slug): ?Page
    {
        $row = $this->getBuilder()->where('slug', $slug)->first();

        return $this->getInstance($row);
    }

    /**
     *
     * @param array|Collection $filter
     * @return ModelCollection|Model[]|PaginatedResultSet|PaginatedModelCollection
     */
    public function getPages($filter)
    {
        $builder = $this->getBuilder();

        return $this->getInstances($builder->paginate(30));
    }

    /**
     * @param $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function restorePageById($id): bool
    {
        return $this->update($this->findOrFail($id), ['deleted_at' => null]);
    }

    public static function getModelClass(): string
    {
        return Page::class;
    }

    public static function getTable(): string
    {
        return 'pages';
    }
}
