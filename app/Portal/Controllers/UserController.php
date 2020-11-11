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

/**
 * Description of UserController
 *
 * @author ivan
 */
class UserController
{

    public function profile()
    {
        $user = \App\Auth\Auth::user();

        return view('portal.profile', compact('user'));
    }

    public function update(Request $request, UpdateUser $service)
    {
        $user = Auth::user();

        if($service->update($user, $request->only('name', 'email'), $request->only('old_password', 'new_password', 'new_password_again'))) {
            Message::success('Sikeres mentés.');
        }

        redirect_route('portal.my_profile');
    }

    public function resetPassword(Request $request, Users $users, Mailer $mailer, UserTokens $userTokens)
    {
        $user = $users->getUserByEmail($request['email']);

        if ($user) {
            $userToken = $userTokens->createUserToken($user, route('portal.recover_password'));

            $mail = ResetPasswordEmail::make($user, $userToken);

            $mailer->to($user->email)->send($mail);
        }

        Message::success('A levelet elküldtük az email címedre.');

        redirect_route('login');
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

        if ($request->postRequestSent()) {
            $ok = $service->changePassword($user, $request->only('new_password', 'new_password_again'));
            if ($ok) {
                $userTokens->delete($token);
                Message::success('Sikeres jelszócsere!');
                Session::forget('last_visited');
                redirect_route('login');
            }
        }

        if (!$user) {
            return view('portal.error', ['message2' => 'Nem létező, vagy törölt felhasználó!']);
        }

        return view('portal.password-reset', compact('user'));
    }
    public function activateUser(Request $request, Users $users, UpdateUser $service, UserTokens $userTokens)
    {
        $token = $userTokens->getByToken($request['token']);

        if (!$token) {
            return view('portal.error', ['message2' => 'Felhasználói aktiválása sikertelen! Hibás token.']);
        }

        if (strtotime($token->expires_at) < time()) {
            return view('portal.error', ['message2' => 'Ennek az linknek az érvényességi ideje lejárt!']);
        }

        $user = $users->getUserByEmail($token->email);

        if ($request->postRequestSent()) {
            $user->activated_at = date('Y-m-d H:i:s');
            $ok = $service->changePassword($user, $request->only('new_password', 'new_password_again'));
            if ($ok) {
                $userTokens->delete($token);
                Message::success('Sikeres regisztráció!');
                Session::forget('last_visited');
                redirect_route('login');
            }
        }

        if (!$user) {
            return view('portal.error', ['message2' => 'Nem létező, vagy törölt felhasználó!']);
        }

        return view('portal.activate-user', compact('user'));
    }
}
