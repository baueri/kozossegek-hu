<?php


namespace App\Repositories;


use App\Models\Page;
use Framework\Repository;

class PageRepository extends Repository
{
    public function findBySlug($slug):Page
    {
        $row = $this->getBuilder()->where('slug', $slug)->first();

        return $this->getInstance($row);
    }
    
    public function getPages()
    {
        return $this->getInstances($this->getBuilder()->paginate(30));
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