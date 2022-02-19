<?php


namespace App\Admin\Components\AdminTable;


interface Editable
{
    public function getEditUrl($model): string;

    public function getEditColumn(): string;
}
