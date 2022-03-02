<?php

namespace App\Helpers;

use Legacy\Institute;

class InstituteHelper
{

    public const INSTITUTE_DIR = 'institutes/images/';

    public static function getImageRelPath($instituteId)
    {
        return "/media/institutes/images/inst_$instituteId.jpg";
    }

    public static function getImageStoragePath($instituteId)
    {
        return _env('STORAGE_PATH') . self::INSTITUTE_DIR . "/inst_$instituteId.jpg";
    }

    public static function getMiserendImagePath(Institute $institute, string $filename)
    {
        return "https://miserend.hu/kepek/templomok/{$institute->miserend_id}/{$filename}";
    }
}
