<?php

namespace App\Enums;

use Framework\Support\Enum;

abstract class NotificationTypes extends Enum
{
    public const LEGAL_NOTICE = 'LEGAL_NOTICE';

    public const NEWS = 'NEWS';

    public const ADMIN_NOTIFICATION = 'ADMIN_NOTIFICATION';
}