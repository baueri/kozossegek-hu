<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use Framework\Http\View\Section;
use Framework\Http\Request;
use App\Repositories\Users;
use App\Auth\Auth;
use Framework\Http\Message;
use \Framework\Support\Password;
use App\Admin\User\UserTable;

class UserController extends AdminController
{

    public function list(UserTable $table)
    {
        return view('admin.user.list', compact('table'));
    }

    public function edit(Request $request, Users $repository)
    {
        $user = $repository->findOrFail($request['id']);
        $my_profile = $user->is(Auth::user());

        return view('admin.user.profile', compact('user', 'my_profile'));
    }

    public function profile()
    {
        $user = \App\Auth\Auth::user();
        $my_profile = true;
        return view('admin.user.profile', compact('user', 'my_profile'));
    }

    public function updateProfile(Request $request, Users $repository)
    {
        $user = Auth::user();

        $user->update($request->only('name', 'email'));

        $repository->save($user);

        Message::success('Sikeres mentés');

        return redirect_route('admin.user.profile');
    }

    public function changeMyPassword(Request $request, Users $repository)
    {
        $password1 = $request['new_password'];
        $password2 = $request['new_password_again'];

        $user = Auth::user();

        if (!Password::verify($request['old_password'], $user->password)) {
            Message::danger('Hibás régi jelszó!');
            return redirect_route('admin.user.profile');
        }

        if (!$password1 || !$password2 || $password1 !== $password2) {
            Message::danger('A két jelszó nem egyezik!');
            return redirect_route('admin.user.profile');
        }

        $user->update(['password' => Password::hash($request['new_password'])]);

        $repository->save($user);

        Message::success('Sikeres jelszócsere');

        return redirect_route('admin.user.profile');
    }
}
