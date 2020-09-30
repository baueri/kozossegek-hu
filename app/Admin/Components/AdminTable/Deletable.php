<?php

namespace App\Admin\Components\AdminTable;

interface Deletable
{
    /**
     * @param $model
     * @return string
     */
    public function getDeleteUrl($model): string;
}