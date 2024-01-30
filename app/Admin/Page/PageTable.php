<?php

namespace App\Admin\Page;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Admin\Components\AdminTable\Editable;
use App\Admin\Components\AdminTable\Traits\SoftDeletable;
use App\Models\Page;
use App\Models\PageStatus;
use App\QueryBuilders\Pages;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;

class PageTable extends PaginatedAdminTable implements Editable
{
    use SoftDeletable;

    protected array $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'created_at' => 'Létrehozva',
        'updated_at' => 'Utoljára módosítva',
    ];

    public function getSlug($slug, Page $page): string
    {
        return "<a href='{$page->getUrl()}' target='_blank'>{$slug}</a>";
    }

    public function getStatus($status): string
    {
        return (new PageStatus($status))->translate();
    }

    public function getUserId(...$params): string
    {
        /** @var \App\Models\Page $page */
        [,$page] = $params;
        return $page->user->name ?? '<i style="color: #aaa">ismeretlen</i>';
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $filter = $this->request->collect();
        if ($this->request->route->getAs() == 'admin.page.trash') {
            $filter['deleted'] = true;
        }

        return $this->getPages($filter);
    }

    public function getSoftDeleteLink($model): string
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

    private function getPages(Collection $filter): PaginatedModelCollection
    {
        $query = Pages::query()
            ->when($filter['status'], fn ($query, $status) => $query->where('status', $status))
            ->when($filter['search'], fn ($query, $search) => $query->where('title', 'like', "%$search%"))
        ;

        if ($filter['deleted']) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        $query->with('user');

        return $query->paginate();
    }
}
