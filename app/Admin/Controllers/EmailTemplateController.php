<?php

namespace App\Admin\Controllers;

use App\Mail\ActiveGroupConfirmEmail;
use App\Mail\GroupContactMail;
use App\Mail\GroupsInactivated;
use App\Mail\NewGroupEmail;
use App\Mail\RegistrationByGroupEmailForFirstUsers;
use App\Mail\RegistrationEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use App\Models\User;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\UserTokens;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\View\View;
use Framework\PasswordGenerator;
use Framework\Support\Arr;

class EmailTemplateController extends AdminController
{
    /**
     * @throws \Exception
     */
    public function registration(UserTokens $userTokens): string
    {
        $user = $this->mintaJanos();
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
        $user = $this->mintaJanos();
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
        $user = $this->mintaJanos();
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
        $mailable = (new NewGroupEmail($this->mintaJanos()));
        $title = 'Új közösség létrehozása (létező fiókkal)';
        return view('admin.email_template', compact('mailable', 'title'));
    }

    /**
     * @throws \Exception
     */
    public function createdGroupWithNewUser(UserTokens $userTokens): string
    {
        $user = $this->mintaJanos();
        $token = $userTokens->make($user, route('portal.user.activate'));
        $mailable = (new NewGroupEmail($user))
            ->withNewUserMessage($token);
        $title = 'Új közösség létrehozása (új fiókkal)';
        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function seasonalNotification(UserTokens $tokens): string
    {
        $user = $this->mintaJanos();
        $user->setRelation('groups', collect([
            new ChurchGroup(['name' => 'Teszt közösség', 'id' => 123]),
            new ChurchGroup(['name' => 'Másik közi', 'id' => 456]),
        ]));

        $groupTokens = collect();

        foreach ($user->groups as $group) {
            $groupTokens[$group->getId()] = $tokens->make($user, route('confirm_group'), now()->addMonth(), ['group_id' => $group->getId()]);
        }
        $mailable = new ActiveGroupConfirmEmail($user, $groupTokens);
        $title = 'Aktív közösség megerősítése';
        return view('admin.email_template', compact('mailable', 'title'));
    }

    public function groupInactivated(): string
    {
        $user = $this->mintaJanos();
        $groups = collect(['Közi 1', 'Közi 2', 'Közi 3'])->map(fn ($name) => ChurchGroup::make(compact('name')));
        $title = 'Értesítés közösség inaktiválásáról';
        $mailable = new GroupsInactivated($user, $groups);
        return view('admin.email_template', compact('mailable', 'title'));
    }

    private function mintaJanos(): User
    {
        return new User(['name' => 'Minta János', 'email' => 'minta_janos@kozossegek.hu']);
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
