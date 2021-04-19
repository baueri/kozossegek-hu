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
    private $admin = false;

    /**
     *
     * @param Institute $institute
     * @return string
     */
    public function getText($institute)
    {
        $miserend_info = $this->admin && $institute->miserend_id ? ' -- miserend.hu --' : '';
        $cityAndDistrict = $institute->city . ($institute->district ? ", $institute->district" : '');
        return "{$institute->name} ({$cityAndDistrict}, {$institute->address}){$miserend_info}";
    }

    public function getId($model)
    {
        return $model->id;
    }

    public function asAdmin(bool $asAdmin)
    {
        $this->admin = $asAdmin;
        return $this;
    }
}
