<?php


namespace App\Repositories;


use App\Models\User;
use Framework\Repository;
use Framework\Support\StringHelper;

class Users extends Repository
{
    public function searchUsers($keyword)
    {
        if (!$keyword) {
            return [];
        }

        $builder = $this->getBuilder()
            ->limit(20);

        if (filter_var($keyword, FILTER_VALIDATE_EMAIL)) {
            $builder->where('email', 'like', "%$keyword%");
        } else {
            $builder->where('name', 'like', "%$keyword%");
        }

        return $this->getInstances($builder->get());
    }

    public function getUsers($filter = [], $limit = 30)
    {
        $builder = $this->getBuilder();

        if ($filter['deleted']) {
            $builder->whereNotNull('deleted_at');
        } else {
            $builder->whereNull('deleted_at');
        }

        $rows = $builder->paginate($limit);

        return $this->getInstances($rows);
    }

    /**
     * @param $auth
     * @return User
     */
    public function findByAuth($auth)
    {
        $builder = $this->getBuilder();

        if (StringHelper::isEmail($auth)) {
            $builder->where('email', $auth);
        } else {
            $builder->where('username', $auth);
        }

        return $this->getInstance($builder->first());
    }

    public function getUsersByIds(array $userIds)
    {
        if (!$userIds) {
            return null;
        }

        $rows = $this->getBuilder()->whereIn('id', $userIds)->get();

        return $this->getInstances($rows);
    }

    public static function getModelClass(): string
    {
        return User::class;
    }

    public static function getTable(): string
    {
        return 'users';
    }
}
