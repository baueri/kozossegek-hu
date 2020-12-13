<?php

namespace App\Helpers;

class InstituteHelper
{

    const STORAGE_DIR = STORAGE_PATH . 'institutes/images/';

    public static function getImageRelPath($instituteId)
    {
        return "/media/institutes/images/inst_$instituteId.jpg";
    }

    public static function getImageStoragePath($instituteId)
    {
        return self::STORAGE_DIR . "/inst_$instituteId.jpg";
    }

}
