<?php


namespace Framework\Database\Repository;


use Framework\Support\Collection;

class RelationManager
{
    /**
     * @var Collection
     */
    protected $relations;

    /**
     * RelationManager constructor.
     */
    public function __construct()
    {
        $this->relations = new Collection();
    }
}
