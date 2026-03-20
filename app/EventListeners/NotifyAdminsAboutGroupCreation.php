<?php

declare(strict_types=1);

namespace App\EventListeners;

use App\Enums\UserRole;
use App\Events\ChurchGroupRegistered;
use App\QueryBuilders\Users;
use Framework\Event\EventListener;
use Framework\Mail\Mailable;
use Framework\Mail\Mailer;
use Throwable;

readonly class NotifyAdminsAboutGroupCreation implements EventListener
{
    public function __construct(
        private Users $users,
        private Mailer $mailer
    ) {

    }

    /**
     * @param ChurchGroupRegistered $event
     */
    public function trigger($event): void
    {
        try {
            $admins = $this->users->notDeleted()->where('user_role', UserRole::SUPER_ADMIN)->get();

            $validationUrl = route('admin.group.validate', $event->churchGroup);

            $mailable = new Mailable();

            $mailable->subject('Új közösség regisztráció történt');

            $mailable->setMessage(<<<EOT
                Új közösség regisztráció történt
                Név: {$event->churchGroup->name}
                Város: {$event->churchGroup->institute->city}
                
                Közösség megtekintése: {$validationUrl}
            EOT
            );

            foreach ($admins as $admin) {
                $this->mailer->to($admin->email, $admin->name);
            }

            $this->mailer->send($mailable);
        } catch (Throwable $e) {
            report($e);
        }
    }
}