<?php

namespace App\Admin\Controllers;

use App\Admin\Settings\EventLog\EventLogAdminTable;
use Framework\Http\Message;
use Framework\Http\View\Section;
use Framework\Maintenance;
use Framework\Http\Request;
use App\Admin\Settings\Services\ErrorLogParser;

class SettingsController extends AdminController
{
    public function settings(Maintenance $maintenance): string
    {
        $maintenance_on = $maintenance->isMaintenanceOn();

        return view('admin.settings', compact('maintenance_on'));
    }

    public function errorLog(Request $request, ErrorLogParser $parser): string
    {
        $errors = $parser->getErrors();

        if ($level = $request['level']) {
            $errors = $errors->filter(
                fn ($error) => $error['severity'] == $level ||
                    (
                        $level == 'FATAL' &&
                        in_array($error['severity'], ['FATAL', 'EXCEPTION', 'SYNTAX_ERROR'])
                    )
            );
        }

        return view('admin.settings.error-log', compact('errors', 'level'));
    }

    public function clearErrorLog()
    {
        unlink(ROOT . 'error.log');

        Message::warning('Hibanapló ürítve');

        redirect_route('admin.error_log');
    }

    public function eventLog(EventLogAdminTable $table, Request $request): string
    {
        Section::add('title', 'Eseménynapló');
        $date_from = $request['date_from'];
        $date_to = $request['date_to'];

        return view('admin.settings.event-log', compact('table', 'date_from', 'date_to'));
    }
}
