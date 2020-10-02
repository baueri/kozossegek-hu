<?php


namespace App\Repositories;


use App\Models\Page;
use Framework\Model\ModelNotFoundException;
use Framework\Repository;
use Framework\Model\PaginatedModelCollection;

class PageRepository extends Repository
{
    protected static $dbColumns = [
        'id', 'title', 'content', 'user_id'
    ];

    public function findBySlug($slug):?Page
    {
        $row = $this->getBuilder()->where('slug', $slug)->first();

        if (!$row) {
            throw new ModelNotFoundException('page not found');
        }

        return $this->getInstance($row);
    }

    /**
     *
     * @param array|Collection $filter
     * @return \Framework\Model\ModelCollection|\Framework\Model\Model[]|\Framework\Database\PaginatedResultSet|PaginatedModelCollection
     */
    public function getPages($filter)
    {
        $builder = $this->getBuilder();

        return $this->getInstances($builder->paginate(30));
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
