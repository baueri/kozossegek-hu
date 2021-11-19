<?php

namespace App\Http\Components\Selectors;

use App\Enums\UserRole;

class UserGroupSelector
{
    public function render($selected_user_group)
    {
        $user_groups = UserRole::get()->map(fn(UserRole $userGroup) => $userGroup->text(), true);
        return view('partials.components.base_selector', [
            'selected_value' => $selected_user_group,
            'values' => $user_groups->all(),
            'name' => 'user_group',
            'placeholder' => '-- Jogosultság --'
        ]);
    }
}