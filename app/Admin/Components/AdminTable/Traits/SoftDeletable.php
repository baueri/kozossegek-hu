<?php

declare(strict_types=1);

namespace App\Admin\Components\AdminTable\Traits;

trait SoftDeletable
{
    public function bootSoftDeletable()
    {
        if (!$this->trashView) {
            $this->columns['delete'] = $this->getIcon('fa fa-trash-alt');
        }
    }
    abstract public function getSoftDeleteLink($model);

    public function getDelete($value, $model, $title = 'lomtárba helyezés')
    {
        $url = $this->getSoftDeleteLink($model);
        return "<a href='$url' title='$title'><i class='fa fa-trash-alt text-danger'></i></a>";
    }
}