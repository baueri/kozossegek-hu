<?php

namespace App\Enums;

enum EventType: string
{
    case search = 'search';
    case group_profile_opened = 'group_profile_opened';
    case group_contact = 'group_contact';
    case group_created = 'group_created';
}
