<?php

namespace App\Helpers;

class InstituteHelper
{
    public static function getInstituteRelPath($instituteId, $thumbnail = false)
    {
        $suffix = $thumbnail ? '_wide' : '';

        return "/media/institutes/inst_$instituteId$suffix.jpg";
    }

    public static function getInstituteAbsPath($instituteId, $thumbnail = false)
    {
        $suffix = $thumbnail ? '_wide' : '';
        
        return ROOT . "public/media/institutes/inst_$instituteId$suffix.jpg";
    }

}
