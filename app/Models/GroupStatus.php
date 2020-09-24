<?php


namespace App\Models;


use App\Enums\GroupStatusEnum;

class GroupStatus extends AbstractSimpleTranslatable
{
    /**
     * @return string
     */
    public function getClass()
    {
        if ($this->name == GroupStatusEnum::ACTIVE) {
            return 'fa fa-check-circle text-success';
        } elseif($this->name == GroupStatusEnum::INACTIVE) {
            return 'fa fa-exclamation-circle text-warning';
        } elseif($this->name == GroupStatusEnum::PENDING) {
            return 'fa fa-sync-alt text-danger';
        }

        return null;

    }
}