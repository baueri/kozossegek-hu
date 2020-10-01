<?php

namespace App\Portal\Responses;

class DistrictResponse extends Select2Response
{
    public function getText($row)
    {
        return $row;
    }
}
