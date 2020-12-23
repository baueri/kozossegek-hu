<?php


namespace App\Admin\Controllers;

use App\Mail\GroupContactMail;
use App\Mail\RegistrationByGroupEmailForFirstUsers;
use App\Mail\RegistrationEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\GroupView;
use App\Models\User;
use App\Repositories\UserTokens;

class EmailTemplateController extends AdminController
{
    public function registration(UserTokens $userTokens)
    {
        $user = new User(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
        $user_token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = RegistrationEmail::make($user, $user_token);
        $title = 'Regisztrációs email sablon';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function registrationByGroup(UserTokens $userTokens)
    {
        $user = new User(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);

        $group = new GroupView();
        $group->name = 'Minta közösség';
        $group->city = 'Szeged';

        $user_token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = RegistrationByGroupEmailForFirstUsers::make($user, $user_token, $group);
        $title = 'Csoportadatok alapján létrehozott felhasználó regisztrációs sablonja';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function passwordReset(UserTokens $userTokens)
    {
        $user = new User(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
        $user_token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = ResetPasswordEmail::make($user, $user_token);
        $title = 'Jelszó visszaállító email sablon';

        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function groupContact()
    {
        $mailable = GroupContactMail::make([
            'name' => 'Minta János',
            'email' => 'minta_janos@kozossegek.hu',
            'message' => "Sziasztok! A kozossegek.hu oldalon találtam rá a közösségetekre.
             Szeretném kérdezni, hogy aktuális-e még a tagfelvétel? Ha igen, csatlakozhatok-e?
             
             Köszi!
             Minta János"
        ]);

        $title = 'Közösségvezetői kapcsolatfelvételi email sablon';

        return view('admin.email_template', compact('mailable', 'title'));
    }
}
