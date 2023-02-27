<?php

namespace Framework\Model;

use Framework\Database\Builder;
use Framework\Model\Exceptions\ModelNotFoundException;
use Framework\Repository;
use RuntimeException;

/**
 * @mixin Builder
 */
class ModelRepositoryBuilder
{
    private Repository $repository;

    private Builder $builder;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
        $this->builder = $repository->getBuilder();
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->builder, $name)) {
            call_user_func_array([$this->builder, $name], $arguments);
            return $this;
        }

        throw new RuntimeException("Call to undefined function {$name}");
    }

    public function count(): int
    {
        return $this->builder->count();
    }

    public function get()
    {
        return $this->repository->getInstances($this->builder->get());
    }

    public function first()
    {
        return $this->repository->getInstance($this->builder->first());
    }

    /**
     * @throws ModelNotFoundException
     */
    public function firstOrFail(): Model
    {
        return $this->repository->getOrFail($this->first());
    }

    public function paginate(?int $perpage = null, ?int $page = null)
    {
        return $this->repository->getInstances($this->builder->paginate($perpage, $page));
    }

    public function exists(): bool
    {
        return $this->builder->exists();
    }

    public function toSql($withBindings = false): string
    {
        return $this->builder->toSql($withBindings);
    }
}
