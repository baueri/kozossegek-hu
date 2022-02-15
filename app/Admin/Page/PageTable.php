<?php

namespace App\Admin\Page;

use App\Admin\Components\AdminTable\AdminTable;
use App\Admin\Components\AdminTable\Deletable;
use App\Admin\Components\AdminTable\Editable;
use App\Models\PageStatus;
use App\Repositories\AdminPageRepository;
use App\Repositories\Users;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

class PageTable extends AdminTable implements Deletable, Editable
{

    protected array $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'created_at' => 'Létrehozva',
        'updated_at' => 'Utoljára módosítva',
    ];

    public function __construct(Request $request, private AdminPageRepository $repository)
    {
        parent::__construct($request);
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
        /** @var \App\Models\Page $page */
        [,$page] = $params;
        return $page->user?->keresztnev() ?? '';
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request->collect();
        if ($this->request->route->getAs() == 'admin.page.trash') {
            $filter['deleted'] = true;
        }

        $pages = $this->repository->getPages($filter);

        $userIds = $pages->pluck('user_id')->unique()->all();

        $users = Users::query()->whereIn('id', $userIds)->get();
        $pages->with($users, 'user', 'user_id');

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
