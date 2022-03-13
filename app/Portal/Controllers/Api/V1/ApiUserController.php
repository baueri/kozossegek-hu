<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\QueryBuilders\Users;
use App\Services\DeleteUser;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Support\Password;

class ApiUserController extends Controller
{
    public function checkEmail(Request $request, Users $users)
    {
        return ['ok' => !$users->byEmail($request['email'])->exists()];
    }

    public function delete(Request $request, DeleteUser $service)
    {
        $user = Auth::user();
        if (!Password::verify($request['password'], $user->password)) {
            return api()->error('Hibás jelszó!');
        }

        $deleted = $service->softDelete($user);

        Auth::logout();

        return api()->response($deleted);
    }
}
