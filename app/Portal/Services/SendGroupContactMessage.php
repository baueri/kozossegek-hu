<?php

namespace App\Portal\Services;

use App\Helpers\HoneyPot;
use App\Mail\GroupContactMail;
use App\Models\GroupView;
use Framework\Application;
use Framework\Exception\UnauthorizedException;
use Framework\Mail\Mailer;
use PHPMailer\PHPMailer\Exception;

class SendGroupContactMessage
{

    /**
     * @var Mailer
     */
    private Mailer $mailer;
    /**
     * @var Application
     */
    private Application $app;


    public function __construct(Application $app, Mailer $mailer)
    {
        $this->mailer = $mailer;
        $this->app = $app;
    }

    /**
     * @throws UnauthorizedException
     * @throws Exception
     */
    public function send(GroupView $group, array $data): bool
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
