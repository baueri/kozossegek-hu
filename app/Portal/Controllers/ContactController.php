<?php

namespace App\Portal\Controllers;

use App\Helpers\HoneyPot;
use Exception;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Request;
use Framework\Mail\Mailable;
use Framework\Mail\Mailer;
use InvalidArgumentException;

class ContactController
{
    /**
     * @throws UnauthorizedException
     */
    public function send(Request $request, Mailer $mailer): array
    {
        HoneyPot::validate('rolunk', $request['website']);

        try {
            if ($request['category'] === 'kapcsolat') {
                $to = config('app.contact_email');
                $subject = 'Új üzenet';
            } else {
                $to = config('app.website_contact_email');
                $subject = 'Új honlappal kapcsolatos üzenet';
            }

            $request->stripTags()
                ->validate('name', 'email', 'message');
            $mailable = (new Mailable())
                ->subject(site_name() . ' - ' . $subject)
                ->with($request->only('name', 'email', 'message', 'category'))
                ->replyTo($request['email'])
                ->view('email_templates:contact_us');

            if ($mailer->to($to)->send($mailable)) {
                return api()->ok('Köszönjük! Üzenetedet elküldtük.');
            }

            return api()->error('Nem sikerült elküldeni az email-t. Kérjük próbáld meg később.');
        } catch (InvalidArgumentException) {
            return api()->error('Minden mező kitöltése kötelező!');
        } catch (Exception $e) {
            report($e);
            return api()->error('Ismeretlen hiba történt, kérjük próbáld meg később.');
        }
    }
}
