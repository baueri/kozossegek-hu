<?php

namespace App\Portal\Responses;

class CitySearchResponse extends Select2Response
{
    public function getText($model)
    {
        return $model->name;
    }
}
