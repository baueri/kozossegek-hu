<?php

namespace App\Portal\Services;

use App\Helpers\HoneyPot;
use App\Mail\GroupContactMail;
use App\Models\ChurchGroupView;
use Framework\Exception\UnauthorizedException;
use Framework\Mail\Mailer;
use PHPMailer\PHPMailer\Exception;

class SendGroupContactMessage
{
    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws UnauthorizedException
     * @throws Exception
     */
    public function send(ChurchGroupView $group, array $data): bool
    {
        HoneyPot::validate('group-contact', $data['website']);

        $mail = new GroupContactMail($data['name'], $data['email'], $data['message']);

        $success = $this->mailer->to($group->group_leader_email)->send($mail);

        log_event('group_contact', [
            'group_id' => $group->id
        ]);

        return $success;
    }
}
