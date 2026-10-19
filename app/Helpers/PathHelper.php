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

    /**
     * Event featured image: ROOT/storage/public/event/… on disk; URL /storage/event/… (public/storage → this tree).
     *
     * @param  string  $hash16  First 16 hex chars of file hash (same as persist logic)
     * @return array{fs: string, url: string} Absolute filesystem path and path for href/src (e.g. /storage/event/ab/cd/….jpg)
     */
    public static function eventFeaturedImageLocation(string $hash16): array
    {
        $a = substr($hash16, 0, 2);
        $b = substr($hash16, 2, 2);
        $file = $hash16 . '.jpg';
        $fs = env('STORAGE_PATH') . 'public' . DS . 'event' . DS . $a . DS . $b . DS . $file;

        return [
            'fs' => $fs,
            'url' => '/storage/event/' . $a . '/' . $b . '/' . $file,
        ];
    }

    /**
     * Larger poster image next to the thumbnail ({hash}.jpg).
     *
     * @param  string  $hash16  First 16 hex chars (same as thumbnail)
     * @return array{fs: string, url: string}
     */
    public static function eventFeaturedImagePosterLocation(string $hash16): array
    {
        $a = substr($hash16, 0, 2);
        $b = substr($hash16, 2, 2);
        $file = $hash16 . '_poster.jpg';
        $fs = env('STORAGE_PATH') . 'public' . DS . 'event' . DS . $a . DS . $b . DS . $file;

        return [
            'fs' => $fs,
            'url' => '/storage/event/' . $a . '/' . $b . '/' . $file,
        ];
    }
}

