<?php

namespace App\Admin\Page;

/**
 * Lomtár
 * Description of TrashPageTable
 *
 * @author ivan
 */
class TrashPageTable extends PageTable
{
    protected $columns = [
        'id' => '#',
        'title' => 'Oldal címe',
        'slug' => 'url',
        'user_id' => 'Szerző',
        'status' => 'Állapot',
        'restore' => '<i class="fa fa-trash-restore">',
        'delete' => '<i class="fa fa-trash"></i>'
    ];
    
    public function getRestore($restore, \App\Models\Page $page)
    {
        $url = route('admin.page.restore', ['id' => $page->id]);
        
        return "<a href='$url' title='visszaállítás'><i class='fa fa-trash-restore text-success'></a>";
    }
    
    public function getDelete(...$params) {
        
        [,$page] = $params;
        
        $url = route('admin.page.delete', ['id' => $page->id]) ;
        return "<a href='$url' title='végleges törlés'><i class='fa fa-trash text-danger'></i></a>";
    }

}
