<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use Framework\Http\View\Section;
use Framework\Http\Request;
use App\Repositories\UserRepository;
use App\Auth\Auth;
use Framework\Http\Message;
use \Framework\Support\Password;

class UserController extends AdminController
{
    public function list()
    {
        Section::add('title', 'Felhasználók');

        return view('admin');
    }

    public function profile()
    {

            // throw new \Exception("kecske");
        $user = \App\Auth\Auth::user();

        return view('admin.user.profile', compact('user'));
    }

    public function updateProfile(Request $request, UserRepository $repository)
    {
        $user = Auth::user();

        $user->update($request->only('name', 'email'));

        $repository->save($user);

        Message::success('Sikeres mentés');

        return redirect_route('admin.user.profile');
    }

    public function changeMyPassword(Request $request, UserRepository $repository)
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
