<?php

namespace App\Admin\Page;

class TrashPageTable extends PageTable
{
    protected array $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'restore' => '<i class="fa fa-trash-restore">',
        'delete' => '<i class="fa fa-trash"></i>'
    ];

    public function getRestore(...$params): string
    {
        [,$page] = $params;

        $url = route('admin.page.restore', $page);

        return "<a href='$url' title='visszaállítás'><i class='fa fa-trash-restore text-success'></a>";
    }

    public function getDelete($t, $page, $title = 'végleges törlés'): string
    {
        $url = route('admin.page.force_delete', $page) ;

        return $this->getDeleteColumn($url, $title);
    }
}
