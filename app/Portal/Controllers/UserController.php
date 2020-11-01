<?php

namespace App\Portal\Controllers;

use Framework\Http\Request;
use App\Repositories\Users;
use App\Auth\Auth;
use App\Services\UpdateUser;
use Framework\Http\Message;

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
    
    public function resetPassword(Request $request, Users $users, \Framework\Mail\Mailer $mailer)
    {
        $user = $users->getUserByEmail($request['email']);
        
        if ($user) {
            $token = md5(time());
            $url = config('app.site_url') . route('portal.recover_password', compact('token'));
            builder('password_reset')->insert([
                'email' => $user->email,
                'token' => $token,
                'expires_at' => (new \DateTime())->modify('+1 day')->format('Y-m-d H:i:s')
            ]);
            
            $mail = new \Framework\Mail\Mailable();
            $mail->subject('kozossegek.hu - elfelejtett jelszó')
                    ->view('mail.forgot-password')
                    ->with(compact('user', 'url'));
            
            $mailer->to($user->email)->send($mail);
        }
        
        Message::success('A levelet elküldtük az email címedre.');
        
        redirect_route('login');
    }
    
    public function recoverPassword(Request $request, Users $users, UpdateUser $service)
    {
        $passwordReset = builder('password_reset')->where('token', $request['token'])->first();
        
        if (!$passwordReset) {
            return view('portal.error', ['message2' => 'Jelszó visszaállítás sikertelen! Hibás token.']);
        }
        
        if (strtotime($passwordReset['expires_at']) < time()) {
            return view('portal.error', ['message2' => 'Ennek a tokennek az érvényességi ideje lejárt!']);
        }
        
        $user = $users->getUserByEmail($passwordReset['email']);
        
        if ($request->postRequestSent()) {
            $ok = $service->changePassword($user, $request->only('new_password', 'new_password_again'));
            builder('password_reset')->where('token', $request['token'])->delete();
            if ($ok) {
                Message::success('Sikeres jelszócsere!');
                \Framework\Http\Session::forget('last_visited');
                redirect_route('login');
            }
        }
        
        if (!$user) {
            return view('portal.error', ['message2' => 'Nem létező, vagy törölt felhasználó!']);
        }
        
        return view('portal.password-reset', compact('user'));
    }
}
