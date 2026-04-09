<?php

declare(strict_types=1);

namespace App\EventListeners;

use App\Enums\UserRole;
use App\Events\UserEventPendingModeration;
use App\QueryBuilders\Users;
use Framework\Event\EventListener;
use Framework\Mail\Mailable;
use Framework\Mail\Mailer;
use Throwable;

readonly class NotifyAdminsAboutPendingUserEvent implements EventListener
{
    public function __construct(
        private Users $users,
        private Mailer $mailer
    ) {
    }

    /**
     * @param UserEventPendingModeration $pending
     */
    public function trigger($pending): void
    {
        try {
            $admins = $this->users->notDeleted()->where('user_role', UserRole::SUPER_ADMIN)->get();
            $editUrl = route('admin.event.edit', ['id' => $pending->event->id]);
            $name = $pending->event->name;

            $mailable = new Mailable();
            $mailable->subject('Új esemény jóváhagyásra vár');
            $mailable->setMessage(<<<EOT
                Egy felhasználó új eseményt hozott létre, amely jóváhagyásra vár.

                Cím: {$name}

                Szerkesztés az adminban: {$editUrl}
                EOT);

            foreach ($admins as $admin) {
                $this->mailer->to($admin->email, $admin->name);
            }

            $this->mailer->send($mailable);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
