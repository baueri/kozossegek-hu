<?php

namespace App\Admin\User;
use Framework\Database\PaginatedResultSetInterface;
use App\Repositories\Users;
use App\Admin\Components\AdminTable\ {
    AdminTable, Deletable, Editable
};

class UserTable extends AdminTable implements Deletable, Editable
{
    protected $columns = [
        'id' => '#',
        'name' => 'Név',
        'username' => 'Felhasználónév',
        'email' => 'Email',
    ];

    /**
     * @var Users
     */
    private $repository;

    public function __construct(Users $repository)
    {
        $this->repository = $repository;
    }

    public function getDeleteUrl($model):string
    {
        return route('admin.user.delete', $model);
    }

    public function getEditUrl($model): string
    {
        return route('admin.user.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'name';
    }

    public function getData(): PaginatedResultSetInterface
    {
        return $this->repository->getUsers();
    }
}
