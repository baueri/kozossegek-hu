<?php

namespace App\Admin\Controllers;

use App\Mail\GroupContactMail;
use App\Mail\NewGroupEmail;
use App\Mail\RegistrationByGroupEmailForFirstUsers;
use App\Mail\RegistrationEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\ChurchGroupView;
use App\Models\UserLegacy;
use App\Repositories\UserTokens;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\View\View;
use Framework\PasswordGenerator;

class EmailTemplateController extends AdminController
{
    /**
     * @throws \Exception
     */
    public function registration(UserTokens $userTokens): string
    {
        $user = new UserLegacy(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
        $user_token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = new RegistrationEmail($user, $user_token);
        $title = 'Regisztrációs email sablon';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    /**
     * @throws \Exception
     */
    public function registrationByGroup(UserTokens $userTokens): string
    {
        $user = new UserLegacy(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
        $password = (new PasswordGenerator(6))->setOpt(PasswordGenerator::OPTION_LOWER, false)->generate();

        $group = new ChurchGroupView(['name' => 'Minta Közösség', 'city' => 'Szeged']);

        $user_token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = new RegistrationByGroupEmailForFirstUsers($user, $password, $user_token, $group);
        $title = 'Csoportadatok alapján létrehozott felhasználó regisztrációs sablonja';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    /**
     * @throws \Exception
     */
    public function passwordReset(UserTokens $userTokens): string
    {
        $user = new UserLegacy(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
        $user_token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = new ResetPasswordEmail($user, $user_token);
        $title = 'Jelszó visszaállító email sablon';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function groupContact(): string
    {
        $message = "Sziasztok! A kozossegek.hu oldalon találtam rá a közösségetekre.
             Szeretném kérdezni, hogy aktuális-e még a tagfelvétel? Ha igen, csatlakozhatok-e?
             
             Köszi!
             Minta János";

        $mailable = new GroupContactMail('Minta János', 'Minta János', $message);

        $title = 'Közösségvezetői kapcsolatfelvételi email sablon';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function createdGroup(): string
    {
        $mailable = (new NewGroupEmail(new UserLegacy(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu'])));
        $title = 'Új közösség létrehozása (létező fiókkal)';
        return view('admin.email_template', compact('mailable', 'title'));
    }

    /**
     * @throws \Exception
     */
    public function createdGroupWithNewUser(UserTokens $userTokens): string
    {
        $user = new UserLegacy(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
        $token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = (new NewGroupEmail($user))
            ->withNewUserMessage($token);
        $title = 'Új közösség létrehozása (új fiókkal)';
        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function saveTemplate(Request $request): array
    {
        Response::asJson();

        $template = View::getPath($request['template']);
        $content = $request['content'];

        file_put_contents($template, $content);

        return [
            'success' => true
        ];
    }
}
