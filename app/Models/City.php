<?php

namespace App\Models;

use App\Portal\BreadCrumb\BreadCrumb;
use App\Portal\BreadCrumb\BreadCrumbable;
use Framework\Model\Entity;

/**
 * @property string $name
 * @property int $county_id
 * @property string $country_code
 * @property string $lat
 * @property string $lon
 * @property CityStat $statistics
 */
class City extends Entity implements BreadCrumbable
{
    public function getBreadCrumb(): BreadCrumb
    {
        return new BreadCrumb([
            [
                'name' => 'Közösségek',
                'position' => 1,
                'url' => route('portal.groups')
            ],
            [
                'name' => $this->name,
                'position' => 2
            ],
        ]);
    }
}
