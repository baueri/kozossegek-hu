<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use App\Admin\User\UserTable;
use App\Auth\Auth;
use App\Models\User;
use App\Repositories\Users;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Support\Password;

class UserController extends AdminController
{

    public function list(UserTable $table)
    {
        return view('admin.user.list', compact('table'));
    }
    
    public function create()
    {
        $user = new User();
        $action = route('admin.user.create');
        
        return view('admin.user.create', compact('user', 'action'));
    }
    
    public function doCreate(Request $request, Users $repository)
    {
        $data = $request->only('username', 'name', 'email', 'user_group');
        $data['password'] = Password::hash(time());
        $user = $repository->create($data);
        
        Message::success('Sikeres létrehozás');
        
        return redirect_route('admin.user.edit', $user);
    }

    public function edit(Request $request, Users $repository)
    {
        $user = $repository->findOrFail($request['id']);
        $my_profile = $user->is(Auth::user());
        $action = route('admin.user.update', $user);
        return view('admin.user.edit', compact('user', 'my_profile', 'action'));
    }
    
    public function update(Request $request, Users $repository)
    {
        /* @var $user User */
        $user = $repository->findOrFail($request['id']);
        $data = $request->only('name', 'email', 'user_group', 'username');
        
        if ($password = $request['new_password']) {
            if ($password !== $request['new_password_again']) {
                Message::danger('A két jelszó nem egyezik!');
                return redirect_route('admin.user.edit', $user);
            }
            
            $data['password'] = Password::hash($password);
        }
        
        $user->update($data);
        $repository->save($user);
        Message::success('Sikeres mentés');
        
        redirect_route('admin.user.edit', $user);
    }

    public function profile()
    {
        $user = Auth::user();
        $my_profile = true;
        $action = route('admin.user.profile.update');
        return view('admin.user.profile', compact('user', 'my_profile', 'action'));
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
    
    public function delete(Request $request, Users $repository)
    {
        $user = $repository->findOrFail($request['id']);
        $repository->delete($user);
        
        Message::warning('Felhasználó törölve');
        
        return redirect_route('admin.user.list');
    }
}
