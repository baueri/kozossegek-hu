<?php

declare(strict_types=1);

namespace App\Admin\Page;

use App\Enums\PageType;

class TrashPageTable extends PageTable
{
    protected array $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'page_type' => 'Típus',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'restore' => '<i class="fa fa-trash-restore">',
    ];

    protected bool $trashView = true;

    public function getPageType($type): string
    {
        return PageType::from($type)->translate();
    }

    public function getRestore(...$params): string
    {
        [,$page] = $params;

        $url = route('admin.page.restore', $page);

        return "<a href='$url' title='visszaállítás'><i class='fa fa-trash-restore text-success'></a>";
    }
}
