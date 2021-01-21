<?php

namespace App\Admin\User;

use Framework\Database\PaginatedResultSetInterface;
use App\Repositories\Users;
use Framework\Http\Request;
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
    private Users $repository;

    /**
     * @param Users $repository
     * @param $request
     */
    public function __construct(Users $repository, Request $request)
    {
        $this->repository = $repository;
        parent::__construct($request);
    }

    public function getDeleteUrl($model): string
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
        $filter = collect($this->request->only('deleted'));
        return $this->repository->getUsers($filter);
    }
}
