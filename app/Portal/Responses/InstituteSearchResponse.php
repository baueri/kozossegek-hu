<?php

namespace App\Portal\Responses;

use App\Models\Institute;

/**
 * @extends Select2Response<Institute>
 */
class InstituteSearchResponse extends Select2Response
{
    private bool $admin = false;

    /**
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

    public function asAdmin(bool $asAdmin): static
    {
        $this->admin = $asAdmin;
        return $this;
    }
}
