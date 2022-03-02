<?php

namespace App\Repositories;

use App\Models\UserLegacy;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

/**
 * @phpstan-extends \Framework\Repository<\App\Models\UserLegacy>
 */
class UsersLegacy extends Repository
{
    public function searchUsers($keyword)
    {
        if (!$keyword) {
            return [];
        }

        $builder = $this->getBuilder()
            ->apply('notDeleted')
            ->limit(20);

        if (filter_var($keyword, FILTER_VALIDATE_EMAIL)) {
            $builder->where('email', 'like', "%$keyword%");
        } else {
            $builder->where('name', 'like', "%$keyword%");
        }

        return $this->getInstances($builder->get());
    }

    /**
     * @return UserLegacy[]|PaginatedModelCollection
     */
    public function getUsers(Collection $filter, ?int $limit = null)
    {
        $builder = $this->getBuilder();

        if ($filter['deleted']) {
            $builder->whereNotNull('deleted_at');
        } else {
            $builder->whereNull('deleted_at');
        }

        if ($filter['order_by']) {
            $builder->orderBy($filter['order_by'], $filter['sort']);
        }

        $rows = $builder->paginate($limit);

        return $this->getInstances($rows);
    }

    public function findByAuth($auth): ?UserLegacy
    {
        if (!$auth) {
            return null;
        }

        $builder = $this->getBuilder();

        $builder->apply('notDeleted');

        if (StringHelper::isEmail($auth)) {
            $builder->where('email', $auth);
        } else {
            $builder->where('username', $auth);
        }

        return $this->getInstance($builder->first());
    }

    /**
     * @param array $userIds
     * @return UserLegacy[]|\Framework\Model\ModelCollection
     */
    public function getUsersByIds(array $userIds)
    {
        if (!$userIds) {
            return null;
        }

        $rows = $this->getBuilder()->whereIn('id', $userIds)->get();

        return $this->getInstances($rows);
    }

    public function getUserByEmail(?string $email): ?UserLegacy
    {
        $row = $this->getBuilder()
            ->where('email', $email)
            ->apply('notDeleted')->first();

        if ($row) {
            return $this->getInstance($row);
        }

        return null;
    }

    public static function getModelClass(): string
    {
        return UserLegacy::class;
    }

    public static function getTable(): string
    {
        return 'users';
    }
}