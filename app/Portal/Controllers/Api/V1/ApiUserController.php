<?php

namespace App\Portal\Controllers\Api\V1;

use App\Repositories\Users;
use Framework\Http\Controller;
use Framework\Http\Request;

class ApiUserController extends Controller
{
    public function checkEmail(Request $request, Users $users)
    {
        $user = $users->getUserByEmail($request['email']);

        return ['ok' => !$user];
    }
}
