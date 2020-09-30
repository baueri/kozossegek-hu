<?php


namespace App\Admin\Components\AdminTable;


interface Editable
{
    /**
     * @param $model
     * @return string
     */
    public function getEditUrl($model): string;

    public function getEditColumn(): string;
}