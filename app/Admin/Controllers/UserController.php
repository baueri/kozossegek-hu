<?php

namespace App\Admin\Controllers;

use App\Admin\User\UserTable;
use App\Auth\Auth;
use App\Enums\UserRole;
use App\Mail\RegistrationEmail;
use App\Models\UserLegacy;
use App\Repositories\UserTokens;
use App\Repositories\UsersLegacy;
use App\Services\DeleteUser;
use Exception;
use Framework\Exception\DuplicateEntryException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Session;
use Framework\Mail\Mailer;
use Framework\Model\ModelNotFoundException;
use Framework\Support\Password;

class UserController extends AdminController
{
    public function list(UserTable $table): string
    {
        $selected_user_group = request()['user_group'];
        return view('admin.user.list', compact('table', 'selected_user_group'));
    }

    public function create(): string
    {
        $user = new UserLegacy(Session::flash('admin.reg.user'));
        $action = route('admin.user.create');
        $groups = UserRole::getTranslated();

        return view('admin.user.create', compact('user', 'action', 'groups'));
    }

    public function doCreate(Request $request, UsersLegacy $repository, UserTokens $passwordResetRepository)
    {
        $data = $request->only('username', 'name', 'email', 'user_group');
        try {
            $existingUser = $repository->query()
                ->where('email', $data['email'])
                ->orWhere('username', $data['username'])
                ->first();

            if ($existingUser) {
                if ($existingUser->email === $data['email']) {
                    $msg = 'Ez az email cím már foglalt';
                } else {
                    $msg = 'Ez a felhasználónév már foglalt';
                }
                throw new DuplicateEntryException($msg);
            }

            $data['password'] = Password::hash(time());
            /* @var $user UserLegacy */
            $user = $repository->create($data);
            $passwordReset = $passwordResetRepository->createActivationToken($user);

            $mailable = new RegistrationEmail($user, $passwordReset);
            (new Mailer($user->email))->send($mailable);

            Message::success('Sikeres létrehozás');
            redirect_route('admin.user.edit', $user);
        } catch (DuplicateEntryException $e) {
            Message::danger($e->getMessage());
            Session::set('admin.reg.user', $data);
            redirect_route('admin.user.create');
        } catch (Exception $e) {
            Message::danger('Váratlan hiba történt');
            report($e);
            Session::set('admin.reg.user', $data);
            redirect_route('admin.user.create');
        }
    }

    /**
     * @throws ModelNotFoundException
     */
    public function edit(Request $request, UsersLegacy $repository): string
    {
        $user = $repository->findOrFail($request['id']);
        $my_profile = $user->is(Auth::user());
        $action = route('admin.user.update', $user);
        $groups = UserRole::getTranslated();
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $user_movement = db()->first('select spiritual_movement_id from spiritual_movement_administrators
            where user_id = ?', [$user->id])['spiritual_movement_id'] ?? null;

        $model = compact('user', 'my_profile', 'action', 'groups', 'spiritual_movements', 'user_movement');
        return view('admin.user.edit', $model);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function update(Request $request, UsersLegacy $repository): void
    {
        /* @var $user UserLegacy */
        $user = $repository->findOrFail($request['id']);
        $data = $request->only('name', 'email', 'user_group', 'username', 'phone_number');

        if ($password = $request['new_password']) {
            if ($password !== $request['new_password_again']) {
                Message::danger('A két jelszó nem egyezik!');
                redirect_route('admin.user.edit', $user);
            }

            $data['password'] = Password::hash($password);
        }

        $user->update($data);
        $repository->save($user);

        if ($spiritualMovementId = $request['spiritual_movement_id']) {
            db()->execute('REPLACE INTO spiritual_movement_administrators (user_id, spiritual_movement_id) VALUES (?, ?)', $user->id, $spiritualMovementId);
        }

        Message::success('Sikeres mentés');

        redirect_route('admin.user.edit', $user);
    }

    public function profile(): string
    {
        $user = Auth::user();
        $my_profile = true;
        $action = route('admin.user.profile.update');
        return view('admin.user.profile', compact('user', 'my_profile', 'action'));
    }

    public function updateProfile(Request $request, UsersLegacy $repository): void
    {
        $user = Auth::user();

        $user->update($request->only('name', 'email'));

        $repository->save($user);

        Message::success('Sikeres mentés');

        redirect_route('admin.user.profile');
    }

    public function changeMyPassword(Request $request, UsersLegacy $repository)
    {
        $password1 = $request['new_password'];
        $password2 = $request['new_password_again'];

        $user = Auth::user();

        if (!Password::verify($request['old_password'], $user->password)) {
            Message::danger('Hibás régi jelszó!');
            redirect_route('admin.user.profile');
        }

        if (!$password1 || !$password2 || $password1 !== $password2) {
            Message::danger('A két jelszó nem egyezik!');
            redirect_route('admin.user.profile');
        }

        $user->update(['password' => Password::hash($request['new_password'])]);

        $repository->save($user);

        Message::success('Sikeres jelszócsere');

        redirect_route('admin.user.profile');
    }

    /**
     * @throws \Framework\Model\ModelNotFoundException
     */
    public function delete(Request $request, UsersLegacy $repository, DeleteUser $service): void
    {
        $user = $repository->findOrFail($request['id']);

        $service->softDelete($user);

        Message::warning('Felhasználó törölve');

        redirect_route('admin.user.list');
    }
}
