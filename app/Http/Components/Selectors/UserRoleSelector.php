<?php

declare(strict_types=1);

namespace App\Http\Components\Selectors;

use App\Enums\UserRole;

class UserRoleSelector
{
    public function render($selected_user_role)
    {
        $user_roles = UserRole::mapTranslated();
        return view('partials.components.base_selector', [
            'selected_value' => $selected_user_role,
            'values' => $user_roles->all(),
            'name' => 'user_role',
            'placeholder' => '-- Jogosultság --'
        ]);
    }
}