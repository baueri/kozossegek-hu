<?php

namespace App\Portal\Responses;

/**
 * Description of InstituteSearchResponse
 *
 * @author ivan
 */
class InstituteSearchResponse extends Select2Response
{
    /**
     * 
     * @param \App\Models\Institute $institute
     * @return string
     */
    public function getText($institute)
    {
        return $institute->name . ' (' . $institute->city . ')';
    }
    
    public function getId($model) {
        return $model->id;
    }

}
