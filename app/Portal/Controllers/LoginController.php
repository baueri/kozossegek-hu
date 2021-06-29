<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Auth\Authenticate;
use App\EntityQueryBuilders\UserLegalNotices;
use App\Exception\EmailTakenException;
use App\Helpers\HoneyPot;
use App\Http\Validators\UserRegisterValidator;
use App\Mail\RegistrationEmail;
use App\Portal\Services\CreateUser;
use App\Repositories\Users;
use App\Repositories\UserTokens;
use App\Services\User\LegalNoticeService;
use Exception;
use Framework\Http\Cookie;
use Framework\Http\Request;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Mail\Mailer;

class LoginController extends PortalController
{
    public function login()
    {
        use_default_header_bg();

        if (Auth::loggedIn()) {
            redirect_route('admin.dashboard');
        }

        return view('portal.login');
    }

    public function doLogin(Request $request, Authenticate $service)
    {
        use_default_header_bg();

        try {
            if (!Cookie::enabled()) {
                Message::danger('A belépéshez engedélyezd a cookie-kat a böngésződben!');
                redirect_route('login');
            }

            $user = $service->authenticate($request['username'], $request['password']);

            app()->get(LegalNoticeService::class)->setLegalNoticeSessionFor($user);

            if (!$user && $service->hasErrors()) {
                Message::danger($service->firstError());
                return view('portal.login');
            }

            Auth::login($user);

            $route = $request['redirect'] ?? route('home');

            redirect(Session::flash('last_visited', $route));

            exit;
        } catch (Exception $e) {
            Message::danger('Váratlan hiba történt.');
            return view('portal.login');
        }
    }

    public function logout()
    {
        Auth::logout();

        Message::success('Sikeres kijelentkezés.');

        redirect_route('login');
    }

    public function register(Request $request, CreateUser $service, UserTokens $tokens, Mailer $mailer, UserRegisterValidator $validator)
    {
        use_default_header_bg();

        $model = [
            'name' => $request['name'],
            'email' => $request['email'],
        ];
        try {
            if ($request->isPostRequestSent()) {
                HoneyPot::validate('register', $request['website']);
                if (!$request['password'] || $request['password'] !== $request['password_again']) {
                    Message::danger('A két jelszó nem egyezik!');
                } else {
                    $user = $service->create($request->collect());
                    if ($user) {
                        $token = $tokens->createActivationToken($user);
                        $message = new RegistrationEmail($user, $token);
                        $mailer->to($request['email'])->send($message);
                        Message::success('Sikeres regisztráció! Az aktiváló linket elküldtük az email címedre.');
                        redirect_route('login');
                    }
                }
            }
            return view('portal.register', $model);
        } catch (EmailTakenException $e) {
            Message::danger('Ez az email cím már foglalt!');
            return view('portal.register', $model);
        }
    }

    /**
     * @param Request $request
     * @param Mailer $mailer
     * @param Users $users
     * @param UserTokens $userTokens
     * @return array
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function resendActivationEmail(Request $request, Mailer $mailer, Users $users, UserTokens $userTokens)
    {
        $user = $users->getUserByEmail($request['email']);

        if (!$user) {
            return api()->error();
        }

        if ($user->isActive()) {
            return api()->error('Ez a felhasználó korábban már aktiválva lett!');
        }

        $token = $userTokens->createActivationToken($user);
        $mailable = new RegistrationEmail($user, $token);

        $ok = $mailer->to($user->email)->send($mailable);
        if ($ok) {
            return api()->ok();
        } else {
            return api()->error();
        }
    }
}
