<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Auth\Authenticate;
use App\Exception\EmailTakenException;
use App\Helpers\HoneyPot;
use App\Mail\RegistrationEmail;
use App\Middleware\RefererMiddleware;
use App\Portal\Services\CreateUser;
use App\QueryBuilders\Users;
use App\QueryBuilders\UserTokens;
use App\Services\User\LegalNoticeService;
use Exception;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Cookie;
use Framework\Http\Request;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Mail\Mailer;
use Framework\Support\Arr;

class LoginController extends PortalController
{
    public function login(): string
    {
        use_default_header_bg();

        if (Auth::loggedIn()) {
            if (Auth::user()->isAdmin()) {
                redirect_route('admin.dashboard');
            }

            redirect_route('home');
        }

        return view('portal.login');
    }

    public function doLogin(Request $request, Authenticate $service): string
    {
        use_default_header_bg();

        try {
            if (!Cookie::enabled()) {
                Message::danger('A belépéshez engedélyezd a cookie-kat a böngésződben!');
                redirect_route('login');
            }

            $user = $service->authenticate($request['username'], $request['password']);

            if (!$user && $service->hasErrors()) {
                Message::danger($service->firstError());
                return view('portal.login');
            }

            Auth::login($user);

            if (str_contains($referer = (string) Arr::get($_SERVER, 'HTTP_REFERER'), get_site_url())) {
                $refererRedirect = $referer;
            }

            $route = $request['redirect'] ?? $refererRedirect ?? route('home');

            log_event('user_login', user: $user);

            redirect(Session::flash('last_visited', $route));
        } catch (Exception $e) {
            report($e);
            Message::danger('Váratlan hiba történt.');
            return view('portal.login');
        }
    }

    public function logout(): void
    {
        Auth::logout();

        Message::success('Sikeres kijelentkezés.');

        redirect_route('login');
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws UnauthorizedException
     * @throws Exception
     */
    public function register(CreateUser $service, UserTokens $tokens, Mailer $mailer, LegalNoticeService $legalNoticeService): string
    {
        $request = $this->request;
        use_default_header_bg();
        $model = [
            'name' => $request['name'],
            'email' => $request['email'],
        ];

        try {
            if ($request->isPostRequestSent()) {
                HoneyPot::validate('register', $request['website']);
                $this->middleware(new RefererMiddleware(route('portal.register')));
                if (!$request['password'] || $request['password'] !== $request['password_again']) {
                    Message::danger('A két jelszó nem egyezik!');
                } else {
                    $user = $service->create($request->collect());
                    $token = $tokens->createActivationToken($user);
                    $legalNoticeService->updateOrInsertCurrentFor($user);
                    $message = new RegistrationEmail($user, $token);
                    $mailer->to($request['email'])->send($message);
                    Message::success('Sikeres regisztráció! Az aktiváló linket elküldtük az email címedre.');
                    redirect_route('login');
                }
            }
            return view('portal.register', $model);
        } catch (EmailTakenException) {
            Message::danger('Ez az email cím már foglalt!');
            return view('portal.register', $model);
        }
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function resendActivationEmail(Mailer $mailer, Users $users, UserTokens $userTokens): array
    {
        $user = $users->byEmail($this->request['email'])->first();

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
            log_event('resend_activation_email', user: $user);
            return api()->ok();
        } else {
            return api()->error();
        }
    }
}
