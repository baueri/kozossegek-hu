<?php

namespace App\Portal\Services;

use App\Mail\GroupContactMail;
use App\Models\Group;
use Framework\Exception\UnauthorizedException;
use Framework\Mail\Mailer;

/**
 * Description of SendContactMessage
 *
 * @author ivan
 */
class SendContactMessage {

    /**
     * @var Mailer
     */
    private $mailer;


    public function __construct(Mailer $mailer)
   {
        $this->mailer = $mailer;
    }
    
    public function send(Group $group, $data)
    {
        $checkTime = $_SESSION['honepot_check_time'];
        $check_hash = $_SESSION['honeypot_check_hash'];

        if (!$checkTime || !$check_hash || time() - $checkTime < 5 || $data['website'] !== $check_hash) {
            throw new UnauthorizedException();
        }
        
        $mail = new GroupContactMail($data);
        
        $this->mailer->to($group->group_leader_email)->send($mail);
    }
}
