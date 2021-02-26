<?php

namespace App\Portal\Responses;

class UserResponse extends Select2Response
{
    public function getText($model)
    {
        return "{$model->name} ({$model->email})";
    }

    public function getId($model)
    {
        return $model->id;
    }
}
