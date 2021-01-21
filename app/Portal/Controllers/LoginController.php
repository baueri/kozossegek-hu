<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Auth\Authenticate;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Controller;
use Framework\Http\Cookie;
use Framework\Http\Request;
use Framework\Http\Message;
use Framework\Http\Session;

/**
 * Description of LoginController
 *
 * @author ivan
 */
class LoginController extends Controller
{
    public function login()
    {
        if (Auth::loggedIn()) {
            redirect_route('admin.dashboard');
        }

        return view('portal.login');
    }

    public function doLogin(Request $request, Authenticate $service)
    {
        try {
            if (!Cookie::enabled()) {
                Message::danger('A belépéshez engedélyezd a cookie-kat a böngésződben!');
                return redirect_route('login');
            }

            $user = $service->authenticate($request['username'], $request['password']);

            Auth::login($user);

            $route = $request['redirect'];
            if (!$route) {
                $route = $user->isAdmin() ? route('admin.dashboard') : route('home');
            }
            redirect(Session::flash('last_visited', $route));
            exit;
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
