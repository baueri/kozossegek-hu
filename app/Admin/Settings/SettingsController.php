<?php

namespace App\Admin\Settings;

use App\Admin\Controllers\AdminController;

class SettingsController extends AdminController
{
    public function settings()
    {
        return $this->view('admin.settings');
    }
}