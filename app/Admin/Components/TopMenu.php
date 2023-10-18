<?php

declare(strict_types=1);

namespace App\Admin\Components;

class TopMenu extends AdminMenu
{
    public function render(): string
    {
        return view('admin.partials.menu_top', [
            'current_menu_item' => $this->menu->first('active'),
        ]);
    }
}