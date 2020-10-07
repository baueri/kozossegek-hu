<?php

namespace App\Portal\Responses;

/**
 * Description of CitySearchResponse
 *
 * @author ivan
 */
class CitySearchResponse extends Select2Response {
    //put your code here
    public function getText($model) {
        return $model['city'];
    }

}
