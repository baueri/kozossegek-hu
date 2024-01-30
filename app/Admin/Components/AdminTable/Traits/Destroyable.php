<?php

declare(strict_types=1);

namespace App\Admin\Components\AdminTable\Traits;

trait Destroyable
{
    public function bootDestroyable()
    {
        if ($this->trashView || !class_uses_trait($this, SoftDeletable::class)) {
            $this->columns['destroy'] = $this->getIcon('fa fa-trash-alt');
        }
    }
    abstract public function getDestroyLink($model);

    public function getDestroy($value, $model, $title = 'végleges törlés')
    {
        $url = $this->getDestroyLink($model);
        return "<a href='$url' title='$title'><i class='fa fa-trash-alt text-danger'></i></a>";
    }
}
