<?php

namespace App\Admin\Page\Services;

use App\Admin\Page\PageTable;
use App\Admin\Page\TrashPageTable;
use App\QueryBuilders\Pages;

class PageListService
{
    public function show(PageTable $table): string
    {
        $is_trash = $table instanceof TrashPageTable;

        $filter = $table->request;
        $trash_count = Pages::query()->trashed()->count();
        return view('admin.page.list', compact('table', 'is_trash', 'filter', 'trash_count'));
    }
}
