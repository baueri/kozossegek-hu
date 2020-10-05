<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use Framework\Maintenance;
use Framework\Http\Request;
use App\Admin\Settings\Services\ErrorLogParser;


class SettingsController extends AdminController
{
    public function settings(Maintenance $maintenance)
    {
        $maintenance_on = $maintenance->isMaintenanceOn();

        return view('admin.settings', compact('maintenance_on'));
    }

    public function errorLog(Request $request, ErrorLogParser $parser)
    {
        $errors = $parser->getErrors();

        if ($level = $request['level']) {
            $errors = $errors->filter(function($error) use ($level){
                return $error['severity'] == $level || ($level == 'FATAL' && in_array($error['severity'], ['FATAL', 'EXCEPTION', 'SYNTAX_ERROR']));
            });
        }

        return view('admin.settings.error-log', compact('errors', 'level'));
    }

    public function clearErrorLog()
    {
        unlink(ROOT . 'error.log');

        \Framework\Http\Message::warning('Hibanapló ürítve');

        return redirect_route('admin.error_log');
    }

    public function eventLog()
    {
        \Framework\Http\View\Section::add('title', 'Eseménynapló');

        return view('admin');
    }
}
