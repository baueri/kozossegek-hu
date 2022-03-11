<?php

namespace App\Enums;

enum EventType
{
    case search;
    case group_profile_opened;
    case group_contact;
    case group_created;
}
