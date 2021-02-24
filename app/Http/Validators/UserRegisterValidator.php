<?php

namespace App\Http\Validators;

use Framework\Support\Validator;

class UserRegisterValidator extends Validator
{
    protected bool $throwExceptionOnFail = true;

    public function getRules(): array
    {
        return [
            'name' => 'required',
            'password' => 'password',
            'email' => 'required|unique:users.email',
        ];
    }
}
