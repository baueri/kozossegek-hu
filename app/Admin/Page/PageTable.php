<?php

declare(strict_types=1);

namespace App\Admin\Page;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Admin\Components\AdminTable\Editable;
use App\Admin\Components\AdminTable\Traits\Destroyable;
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
    use Destroyable;

    protected array $columns = [
        'id' => '#',
        'title' => 'Cím',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'created_at' => 'Létrehozva',
        'updated_at' => 'Utoljára módosítva',
    ];

    protected string $emptyTrashRoute = 'admin.page.empty_trash';

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
        /** @var Page $page */
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

    public function getDestroyLink($model): string
    {

        return route('admin.page.force_delete', $model);
    }

    public function getEditUrl($model): string
    {
        return route('admin.page.edit', $model);
    }

    public function getTitle($title, $model): string
    {
        $count = '';
        if ($model->page_type === 'announcement') {
            $count = " ({$model->seenAnnouncements_count})";
        }
        return $this->getEdit($title . $count, $model);
    }

    public function getEditColumn(): string
    {
        return 'title';
    }

    private function getPages(Collection $filter): PaginatedModelCollection
    {
        $type = $filter->get('page_type', 'page');
        $query = Pages::query()
            ->when($type && !$this->trashView, fn ($query) => $query->where('page_type', $type))
            ->when($filter['status'], fn ($query, $status) => $query->where('status', $status))
            ->when($filter['search'], fn ($query, $search) => $query->where('title', 'like', "%$search%"))
        ;

        if ($filter['deleted']) {
            $query->whereNotNull('deleted_at');
        } else {
            $query->whereNull('deleted_at');
        }

        $query->with('user');

        if ($this->request->get('page_type') === 'announcement') {
            $query->withCount('seenAnnouncements');
        }

        return $query->paginate();
    }
}
