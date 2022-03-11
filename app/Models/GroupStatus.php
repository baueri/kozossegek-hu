<?php


namespace App\Models;


use App\Enums\GroupStatus;

class GroupStatus extends AbstractSimpleTranslatable
{
    /**
     * @return string
     */
    public function getClass()
    {
        if ($this->name == GroupStatus::active->name) {
            return 'fa fa-check-circle text-success';
        } elseif($this->name == GroupStatus::inactive->name) {
            return 'fa fa-moon text-muted';
        }

        return null;

    }
}
