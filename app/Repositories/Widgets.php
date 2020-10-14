<?php

namespace App\Repositories;

use Framework\Model\ModelNotFoundException;

class Widgets extends \Framework\Repository
{
    public function getByUniqId($uniqid)
    {
        $row = $this->getBuilder()->where('`uniqid`', $uniqid)->first();

        if (!$row) {
            throw new ModelNotFoundException;
        }

        return $this->getInstance($row);
    }

    public static function getModelClass():string
    {
        return \App\Models\Widget::class;
    }

    public static function getTable():string
    {
        return 'widgets';
    }

}
