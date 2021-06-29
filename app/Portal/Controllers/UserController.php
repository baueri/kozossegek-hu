<?php

namespace App\Portal\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Repositories\UserTokens;
use Framework\Http\Request;
use App\Repositories\Users;
use App\Auth\Auth;
use App\Services\UpdateUser;
use Framework\Http\Message;
use Framework\Http\Session;
use Framework\Mail\Mailer;
use PHPMailer\PHPMailer\Exception;

class UserController extends PortalController
{
    public function profile()
    {
        $user = Auth::user();

        return view('portal.profile', compact('user'));
    }

    public function update(Request $request, UpdateUser $service)
    {
        $user = Auth::user();
        $changes = $request->only('name', 'email', 'phone_number');

        $passwordChange = $request->only('old_password', 'new_password', 'new_password_again');

        if ($service->update($user, $changes, $passwordChange)) {
            Message::success('Sikeres mentés.');
        }

        redirect_route('portal.my_profile');
    }

    public function forgotPassword()
    {
        use_default_header_bg();

        return view('portal.forgot-password');
    }

    /**
     * @param Request $request
     * @param Users $users
     * @param Mailer $mailer
     * @param UserTokens $userTokens
     * @throws Exception
     */
    public function resetPassword(Request $request, Users $users, Mailer $mailer, UserTokens $userTokens)
    {
        $user = $users->getUserByEmail($request['email']);

        if ($user) {
            $userToken = $userTokens->createUserToken($user, route('portal.recover_password'));

            $mail = ResetPasswordEmail::make($user, $userToken);

            $mailer->to($user->email)->send($mail);

            Message::success('A levelet elküldtük az email címedre.');

            redirect_route('login');
        }

        Message::danger('Nincs ilyen felhasználó!');

        redirect_route('portal.forgot_password');
    }

    public function recoverPassword(Request $request, Users $users, UpdateUser $service, UserTokens $userTokens)
    {
        $token = $userTokens->getByToken($request['token']);

        if (!$token) {
            return view('portal.error', ['message2' => 'Jelszó visszaállítás sikertelen! Hibás token.']);
        }

        if (strtotime($token->expires_at) < time()) {
            return view('portal.error', ['message2' => 'Ennek a tokennek az érvényességi ideje lejárt!']);
        }

        $user = $users->getUserByEmail($token->email);

        if ($request->isPostRequestSent()) {
            $ok = $service->changePassword($user, $request->only('new_password', 'new_password_again'));
            if ($ok) {
                $userTokens->delete($token);
                Message::success('<b>Sikeres jelszócsere!</b> Most már be tudsz lépni az új jelszavaddal.');
                Session::forget('last_visited');
                redirect_route('login');
            }
        }

        if (!$user) {
            return view('portal.error', ['message2' => 'Nem létező, vagy törölt felhasználó!']);
        }

        return view('portal.password-reset', compact('user'));
    }


    public function activateUser(Request $request, Users $users, UserTokens $userTokens)
    {
        try {
            $token = $userTokens->getByToken($request['token']);

            if (!$token) {
                return view('portal.error', ['message2' => 'Felhasználói aktiválása sikertelen! Hibás token.']);
            }

            if (strtotime($token->expires_at) < time()) {
                return view('portal.error', ['message2' => 'Ennek a linknek az érvényességi ideje lejárt!']);
            }

            $user = $users->getUserByEmail($token->email);

            if (!$user) {
                return view('portal.error', ['message2' => 'Nem létező, vagy törölt felhasználó!']);
            }

            $user->activated_at = date('Y-m-d H:i:s');
            $users->save($user);
            $userTokens->delete($token);
            Auth::logout();
            Auth::login($user);
            Message::success('Sikeres fiók aktiválás!');
            Session::forget('last_visited');
            redirect_route('portal.my_profile');
        } catch (\Exception $e) {
            process_error($e);
            raise_500('Felhasználó aktiválása nem sikerült');
        }
    }
}
