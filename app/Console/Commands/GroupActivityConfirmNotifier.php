<?php

namespace App\Console\Commands;

use App\Mail\ActiveGroupConfirmEmail;
use App\QueryBuilders\ChurchGroups;
use Framework\Console\Command;
use Framework\Mail\Mailer;

class GroupActivityConfirmNotifier implements Command
{

    public static function signature(): string
    {
        return 'group:notify-groups';
    }

    public function handle(): void
    {
        $groups = ChurchGroups::query()
            ->with('maintainer')
            ->orderBy('created_at')
            ->shouldNotify()
            ->limit(1)
            ->get();

        $mailer = new Mailer();

        foreach ($groups as $group) {
            $mailable = new ActiveGroupConfirmEmail($group, $group->maintainer);
            $mailer->to($group->maintainer->email, $group->maintainer->name)
                ->send($mailable);
        }
    }
}
