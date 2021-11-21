<?php

namespace App\Admin\Page;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\PageStatus;
use App\Repositories\AdminPageRepository;
use App\Repositories\PageRepository;
use App\Repositories\Users;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

class PageTable extends AdminTable implements Deletable, Editable
{
    private AdminPageRepository $repository;

    private Users $userRepository;

    protected $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'created_at' => 'Létrehozva',
        'updated_at' => 'Utoljára módosítva',
    ];

    public function __construct(Request $request, AdminPageRepository $repository, Users $userRepository)
    {
        parent::__construct($request);
        $this->repository = $repository;
        $this->userRepository = $userRepository;
    }

    public function getSlug($slug): string
    {
        $url = route('portal.page', compact('slug')) ;
        return "<a href='$url' target='_blank'>$url</a>";
    }

    public function getStatus($status): string
    {
        return (new PageStatus($status))->translate();
    }

    public function getUserId(...$params): string
    {
        [,$page] = $params;
        return $page->user->name ?? '';
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request->collect();
        if ($this->request->route->getAs() == 'admin.page.trash') {
            $filter['deleted'] = true;
        }

        $pages = $this->repository->getPages($filter);

        $userIds = $pages->pluck('user_id')->unique()->all();

        $pages->with($this->userRepository->getUsersByIds($userIds), 'user', 'user_id');

        return $pages;
    }

    public function getDeleteUrl($model): string
    {
        return route('admin.page.delete', $model);
    }

    public function getEditUrl($model): string
    {
        return route('admin.page.edit', $model);
    }

    public function getEditColumn(): string
    {
        return 'title';
    }
}
