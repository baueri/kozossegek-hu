<?php

namespace App\Portal\Responses;

use App\Models\Institute;

/**
 * Description of InstituteSearchResponse
 *
 * @author ivan
 */
class InstituteSearchResponse extends Select2Response
{
    /**
     *
     * @param Institute $institute
     * @return string
     */
    public function getText($institute)
    {
        $cityAndDistrict = $institute->city . ($institute->district ? ", $institute->district" : '');
        return "{$institute->name} ({$cityAndDistrict})";
    }

    public function getId($model)
    {
        return $model->id;
    }
}
