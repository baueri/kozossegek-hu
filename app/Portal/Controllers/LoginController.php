<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Auth\Authenticate;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Request;
use Framework\Http\Message;
use Framework\Http\Session;

/**
 * Description of LoginController
 *
 * @author ivan
 */
class LoginController extends \Framework\Http\Controller
{
    public function login()
    {
        if (Auth::loggedIn()) {
            redirect_route('admin.dashboard');
        }

        return $this->view('portal.login');
    }

    public function doLogin(Request $request, Authenticate $service)
    {
        try {
            $user = $service->authenticate($request['username'], $request['password']);

            Auth::login($user);

            redirect(Session::flash('last_visited', route('admin.dashboard')));

        } catch (UnauthorizedException $e) {

            Message::danger('Hibás felhasználónév vagy jelszó');

            return view('portal.login');
        }
    }

    public function logout()
    {
        Auth::logout();

        Message::success('Sikeres kijelentkezés.');

        redirect_route('login');
    }
}
