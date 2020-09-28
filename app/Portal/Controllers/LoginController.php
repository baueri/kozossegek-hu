<?php

namespace App\Portal\Controllers;

/**
 * Description of LoginController
 *
 * @author ivan
 */
class LoginController extends \Framework\Http\Controller
{
    public function login()
    {
        return $this->view('portal.login');
    }
    
    public function doLogin(\Framework\Http\Request $request)
    {
        redirect('login');
    }
}
