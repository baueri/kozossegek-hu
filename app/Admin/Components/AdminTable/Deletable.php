<?php

namespace App\Admin\Components\AdminTable;

interface Deletable
{
    public function getDeleteUrl($model): string;
}