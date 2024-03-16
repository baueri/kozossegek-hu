<?php

namespace App\Admin\Page\Services;

use App\Admin\Page\PageTable;
use App\Admin\Page\TrashPageTable;
use App\QueryBuilders\Pages;

class PageListService
{
    public function show(PageTable $table): string
    {
        $page_type = $table->request->get('page_type', 'page');
        $is_trash = $table instanceof TrashPageTable;

        $filter = $table->request;
        return view('admin.page.list', compact('table', 'is_trash', 'filter', 'page_type'));
    }
}
