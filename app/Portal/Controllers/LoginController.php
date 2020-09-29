<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Auth\Authenticate;
use Framework\Http\Request;

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
            redirect('admin.dashboard');
        }

        return $this->view('portal.login');
    }
    
    public function doLogin(Request $request, Authenticate $service)
    {
        $user = $service->authenticate($request['username'], $request['password']);

        Auth::login($user);

        redirect('admin.dashboard');
    }
}
