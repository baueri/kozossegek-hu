<?php

namespace App\Helpers;

class GroupHelper
{
    public static function getRelpath(?int $groupId): string
    {
        if (!$groupId) {
            return '';
        }

        $groupIdFull = str_pad($groupId, 7, '0', STR_PAD_LEFT);
        $groupIdReversed = strrev($groupIdFull);
        preg_match_all('/([0-9]{2})/', $groupIdReversed, $matches);

        return $matches[0][0] . DS . $matches[0][1] . DS . $groupIdFull . DS;
    }

    public static function getStoragePath(?int $groupId): string
    {
        if (!$groupId) {
            return '';
        }

        $root = _env('STORAGE_PATH') . 'groups' . DS;

        return $root . static::getRelpath($groupId);
    }

    public static function getPublicImagePath(int $groupId): string
    {
        return "/media/groups/images/{$groupId}_1.jpg";
    }
}
