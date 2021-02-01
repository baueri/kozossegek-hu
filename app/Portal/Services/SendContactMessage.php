<?php

namespace App\Portal\Services;

use App\Mail\GroupContactMail;
use App\Models\Group;
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
     * @param Group $group
     * @param $data
     * @return bool
     * @throws UnauthorizedException
     * @throws Exception
     */
    public function send(Group $group, $data): bool
    {
        $checkTime = $_SESSION['honepot_check_time'];
        $check_hash = $_SESSION['honeypot_check_hash'];

        if (!$checkTime || !$check_hash || time() - $checkTime < 5 || $data['website'] !== $check_hash) {
            throw new UnauthorizedException();
        }

        $mail = new GroupContactMail($data);

        return $this->mailer->to($group->group_leader_email)->send($mail);
    }
}
