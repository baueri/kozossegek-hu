<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Enums;

/**
 * Description of AgeGroup
 *
 * @author ivan
 */
class AgeGroupEnum extends \Framework\Support\Enum
{
    const TINEDZSER = 'tinedzser';
    const FIATAL_FELNOTT = 'fiatal_felnott';
    const KOZEPKORU = 'kozepkoru';
    const NYUGDIJAS = 'nyugdijas';

    public static function getIcon($ageGroup)
    {
        if ($ageGroup === self::TINEDZSER) {
            return 'fa fa-child';
        } elseif($ageGroup === self::FIATAL_FELNOTT) {
            return 'fa fa-user-graduate';
        } elseif($ageGroup === self::KOZEPKORU) {
            return 'fa fa-work';
        } elseif ($ageGroup === self::NYUGDIJAS) {
            return '';
        }
    }
}
