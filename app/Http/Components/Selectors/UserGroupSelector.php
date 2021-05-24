<?php

namespace App\Http\Components\Selectors;

use App\Enums\UserGroup;

class UserGroupSelector
{
    public function render($selected_user_group)
    {
        $user_groups = UserGroup::get()->map(fn(UserGroup $userGroup) => $userGroup->text(), true);
        return view('partials.components.base_selector', [
            'selected_value' => $selected_user_group,
            'values' => $user_groups->toArray(),
            'name' => 'user_group',
            'placeholder' => '-- Jogosultság --'
        ]);
    }
}