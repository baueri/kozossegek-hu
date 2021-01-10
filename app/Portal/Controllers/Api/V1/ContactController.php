<?php

namespace App\Portal\Controllers\Api\V1;

use App\Helpers\HoneyPot;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Request;
use Framework\Mail\Mailable;
use Framework\Mail\Mailer;
use Framework\Support\StringHelper;
use InvalidArgumentException;

class ContactController
{
    /**
     * @param Request $request
     * @param Mailer $mailer
     * @return array
     * @throws UnauthorizedException
     */
    public function send(Request $request, Mailer $mailer)
    {
        HoneyPot::validate('/rolunk', $request['website']);

        try {
            $request->stripTags()
                ->validate('name', 'email', 'message');
            $mailable = Mailable::make()
                ->subject('kozossegek.hu - új üzenet')
                ->with($request->only('name', 'email', 'message'))
                ->view('mail.contact_us');

            if ($mailer->to('birkaivan@gmail.com')->send($mailable)) {
                return api()->ok('Köszönjük! Üzenetedet elküldtük.');
            }

            return api()->error('Nem sikerült elküldeni az email-t. Kérjük próbáld meg később.');


        } catch (InvalidArgumentException $e) {
            return api()->error('Minden mező kitöltése kötelező!');
        } catch (\Exception $e) {
            return api()->error('Ismeretlen hiba történt, kérjük próbáld meg később.');
        }
    }
}
