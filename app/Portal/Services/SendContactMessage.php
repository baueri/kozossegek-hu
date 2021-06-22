<?php

namespace App\Portal\Services;

use App\Helpers\HoneyPot;
use App\Mail\GroupContactMail;
use App\Models\Group;
use App\Models\GroupView;
use App\Traits\LogsEvent;
use Framework\Application;
use Framework\Exception\UnauthorizedException;
use Framework\Mail\Mailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Description of SendContactMessage
 *
 * @author ivan
 */
class SendContactMessage
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
        $checkTime = $_SESSION['honepot_check_time'];
        $check_hash = $_SESSION['honeypot_check_hash'];

        if (!$checkTime || !$check_hash || time() - $checkTime < 5 || $data['website'] !== $check_hash) {
            throw new UnauthorizedException();
        }

        $mail = new GroupContactMail($data['name'], $data['email'], $data['message']);

        $success = $this->mailer->to($group->group_leader_email)->send($mail);

        log_event('group_contact', [
            'group_id' => $group->id
        ]);

        return $success;
    }
}
