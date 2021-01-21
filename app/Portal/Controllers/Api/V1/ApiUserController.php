<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\Repositories\Users;
use App\Services\DeleteUser;
use Framework\Http\Controller;
use Framework\Http\Request;

class ApiUserController extends Controller
{
    public function checkEmail(Request $request, Users $users)
    {
        $user = $users->getUserByEmail($request['email']);

        return ['ok' => !$user];
    }

    public function delete(DeleteUser $service)
    {
        $deleted = $service->softDelete(Auth::user());

        Auth::logout();

        return api()->response($deleted);
    }
}
