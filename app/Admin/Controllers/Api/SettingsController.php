<?php

namespace App\Admin\Controllers\Api;

use Framework\Http\Request;
use Framework\Maintenance;

class SettingsController
{
    public function toggleMaintenance(Request $request, Maintenance $maintenance): array
    {
        if ($request['toggle']) {
            $maintenance->down();
        } else {
            $maintenance->up();
        }

        return ['success' => true];
    }
}
