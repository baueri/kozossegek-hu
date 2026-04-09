<?php

declare(strict_types=1);

namespace App\Helpers;

class PathHelper
{
    protected static function getRelpath(?string $uid): string
    {
        if (!$uid) {
            return '';
        }

        $fullUid = str_pad($uid, 7, '0', STR_PAD_LEFT);
        $uidRev = strrev($fullUid);
        preg_match_all('/([0-9]{2})/', $uidRev, $matches);

        return $matches[0][0] . DS . $matches[0][1] . DS . $fullUid . DS;
    }

    public static function getStoragePath(string $dir, string $uid): string
    {
        if (!$uid) {
            return '';
        }

        $root = env('STORAGE_PATH') . $dir . DS;

        return $root . static::getRelpath($uid);
    }

    public static function getPublicImagePath(int $uid): string
    {
        return "/media/groups/images/{$uid}_1.jpg";
    }
}
