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
    
    public function passwordChange(Request $request)
    {
        
    }
}
