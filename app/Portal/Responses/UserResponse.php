<?php

namespace App\Portal\Responses;

/**
 * Description of CitySearchResponse
 *
 * @author ivan
 */
class UserResponse extends Select2Response {
    //put your code here
    public function getText($model) {
        return "{$model->name} ({$model->email})";
    }
    
    public function getId($model) {
        return $model->id;
    }

}
