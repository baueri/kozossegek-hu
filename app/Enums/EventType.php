<?php

namespace App\Enums;

use Framework\Support\Collection;

enum EventType: string
{
    use EnumTrait;

    case search = 'search';
    case group_profile_opened = 'group_profile_opened';
    case group_contact = 'group_contact';
    case group_created = 'group_created';

    case referer_fail = 'referer_fail';
    case csrf_fail = 'csrf_fail';
    case honeypot_fail = 'honeypot_fail';

    /**
     * @return Collection<static>
     */
    public static function spamLogs(): Collection
    {
        return collect([self::referer_fail, self::csrf_fail, self::honeypot_fail]);
    }
}
