<?php

declare(strict_types=1);

namespace App\Portal\Services;

use App\Helpers\HoneyPot;
use App\Mail\GroupContactMail;
use App\Models\ChurchGroupView;
use Framework\Exception\UnauthorizedException;
use Framework\Mail\Mailer;
use Framework\Support\StringHelper;
use PHPMailer\PHPMailer\Exception;

class SendGroupContactMessage
{
    public function __construct(
        private readonly Mailer $mailer
    ) {
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

        log_event('group_contact', ['group_id' => $group->id]);

        $this->reportRandomWords($data['message'], $data['email']);

        return $success;
    }

    /**
     * Silently report some random words to ensure no spam messages are sent to group leraders
     */
    public function reportRandomWords(string $message, string $email): void
    {
        try {
            $randomWords = collect(explode(' ', str_replace(['.', '?', ','], '', $message)))
                ->filter(fn ($word) => mb_strlen($word) > 3  && $word[0] == mb_strtolower($word[0])) // ignore short and upper case words, e.g. names
                ->shuffle()
                ->take(10)
                ->implode(', ');

            // Hide email address behind a mask to ensure privacy
            $maskedEmail = StringHelper::maskEmail($email);

            report("email: {$maskedEmail}\n contact message random words:\n {$randomWords}");
        } catch (\Exception $e) {
            report($e);
        }
    }
}
