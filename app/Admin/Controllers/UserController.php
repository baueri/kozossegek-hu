<?php

namespace App\Admin\Controllers;

use App\Admin\User\UserTable;
use App\Auth\Auth;
use App\Enums\UserRole;
use App\Mail\RegistrationEmail;
use App\Models\User;
use App\QueryBuilders\Users;
use App\QueryBuilders\UserTokens;
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
        $user = new User(Session::flash('admin.reg.user'));
        $action = route('admin.user.create');
        $groups = UserRole::getTranslated();

        return view('admin.user.create', compact('user', 'action', 'groups'));
    }

    public function doCreate(Request $request, Users $repository, UserTokens $passwordResetRepository)
    {
        $data = $request->only('username', 'name', 'email', 'user_group');
        try {
            $existingUser = $repository->query()->where('email', $data['email'])
                ->orWhere('username', $data['username'])
                ->first();

            if ($existingUser) {
                throw new DuplicateEntryException($existingUser->email === $data['email'] ? 'Ez az email cím már foglalt' : 'Ez a felhasználónév már foglalt');
            }

            $data['password'] = Password::hash(time());
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
    public function edit(Request $request, Users $repository): string
    {
        $user = $repository->findOrFail($request['id']);
        $my_profile = Auth::is($user);
        $action = route('admin.user.update', $user);
        $groups = UserRole::getTranslated();
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $user_movement = db()->first('select spiritual_movement_id from spiritual_movement_administrators
            where user_id = ?', [$user->id])['spiritual_movement_id'] ?? null;

        $model = compact('user', 'my_profile', 'action', 'groups', 'spiritual_movements', 'user_movement');
        return view('admin.user.edit', $model);
    }

    public function managedGroups(Request $request, Users $repository)
    {
        $user = $repository->findOrFail($request['id']);
        $managedGroups = db()->select('select * from managed_church_groups m join church_groups g on g.id = m.group_id where m.user_id = ? order by name', [$user->getId()]);

        return view('admin.user.managed_groups', compact('user', 'managedGroups'));
    }

    /**
     * @throws ModelNotFoundException
     */
    public function update(Request $request, Users $repository): void
    {
        $user = $repository->findOrFail($request['id']);
        $data = $request->only('name', 'email', 'user_group', 'username', 'phone_number');

        if ($password = $request['new_password']) {
            if ($password !== $request['new_password_again']) {
                Message::danger('A két jelszó nem egyezik!');
                redirect_route('admin.user.edit', $user);
            }

            $data['password'] = Password::hash($password);
        }

        $repository->save($user, $data);

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

    public function updateProfile(Request $request, Users $repository): void
    {
        $user = Auth::user();

        $repository->save($user, $request->only('name', 'email'));

        Message::success('Sikeres mentés');

        redirect_route('admin.user.profile');
    }

    public function changeMyPassword(Request $request, Users $repository)
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

        $repository->save($user, ['password' => Password::hash($request['new_password'])]);

        Message::success('Sikeres jelszócsere');

        redirect_route('admin.user.profile');
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(Request $request, Users $repository, DeleteUser $service): void
    {
        $user = $repository->findOrFail($request['id']);

        $service->softDelete($user);

        Message::warning('Felhasználó törölve');

        redirect_route('admin.user.list');
    }
}
