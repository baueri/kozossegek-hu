<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Auth\Authenticate;
use App\Mail\RegistrationEmail;
use App\Repositories\Users;
use App\Repositories\UserTokens;
use Exception;
use Framework\Http\Controller;
use Framework\Http\Cookie;
use Framework\Http\Request;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Mail\Mailer;

class LoginController extends Controller
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
                return redirect_route('login');
            }

            $user = $service->authenticate($request['username'], $request['password']);

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

        $token = $userTokens->createUserToken($user, route('portal.user.activate'));
        $mailable = new RegistrationEmail($user, $token);

        $ok = $mailer->to($user->email)->send($mailable);
        if ($ok) {
            return api()->ok();
        } else {
            return api()->error();
        }
    }
}
