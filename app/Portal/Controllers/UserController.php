<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Portal\Controllers;

/**
 * Description of UserController
 *
 * @author ivan
 */
class UserController {
    public function profile()
    {
        $user = \App\Auth\Auth::user();
        
        return view('portal.profile', compact('user'));
    }
}
