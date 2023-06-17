<?php

namespace App\Helpers;

use App\Models\Institute;

class InstituteHelper
{
    public const INSTITUTE_DIR = 'institutes/images/';

    public static function getImageRelPath($instituteId): string
    {
        return "/media/institutes/images/inst_$instituteId.jpg";
    }

    public static function getImageStoragePath($instituteId): string
    {
        return env('STORAGE_PATH') . self::INSTITUTE_DIR . DS . "inst_$instituteId.jpg";
    }

    public static function getMiserendImagePath(Institute $institute, string $filename): string
    {
        return "https://miserend.hu/kepek/templomok/{$institute->miserend_id}/{$filename}";
    }
}
