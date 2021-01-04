<?php

namespace App\Helpers;

use App\Models\AgeGroup;
use App\Models\Group;
use App\Models\User;
use Framework\Support\Collection;

class GroupHelper
{
    /**
     *
     * @param string $ageGroup
     * @return string
     */
    public static function parseAgeGroup($ageGroup)
    {
        $ageGroups = static::getAgeGroups($ageGroup);

        if ($ageGroups->count() > 1) {
            return 'vegyes';
        }

        return $ageGroups->implode(', ');
    }

    /**
     *
     * @param string $ageGroup
     * @return Collection
     */
    public static function getAgeGroups(string $ageGroup)
    {
        return (new Collection(explode(',', $ageGroup)))
            ->filter()
            ->make(AgeGroup::class)
            ->keyBy('name')
            ->map(function ($ageGroup) {
                return $ageGroup->translate();
            }, true);
    }

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

        $root = STORAGE_PATH . 'groups' . DS;

        return $root . static::getRelpath($groupId);
    }

    public static function isGroupEditableBy(Group $group, User $user): bool
    {
        return $user->isAdmin() || $user->id == $group->user_id;
    }
}
