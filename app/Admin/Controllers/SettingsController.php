<?php

namespace App\Admin\Controllers;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Admin\Settings\EventLog\EventLogAdminTable;
use App\Console\Commands\Cron\DailyCron;
use App\Console\Commands\Cron\MonthlyCron;
use App\Services\SystemAdministration\SiteMap\SiteMapGenerator;
use Framework\Console\Command;
use Framework\Database\PaginatedResultSet;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Message;
use Framework\Http\View\Section;
use Framework\Maintenance;
use Framework\Http\Request;
use App\Admin\Settings\Services\ErrorLogParser;
use Throwable;

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

    public function scheduledTasks(): string
    {
        $table = function (string $command): PaginatedAdminTable {
            return new class($command, request()) extends PaginatedAdminTable
            {
                protected array $columns = [
                    'signature' => 'azonosító',
                    'description' => 'Leírás'
                ];

                protected array $columnClasses = [
                    'signature' => 'signature'
                ];

                protected bool $withPager = false;

                public function __construct(private readonly string $command, Request $request)
                {
                    parent::__construct($request);
                }

                protected function getData(): PaginatedResultSetInterface
                {
                    return new PaginatedResultSet(
                        array_map(
                            fn (Command $command) =>
                            ['signature' => $command::signature(), 'description' => $command->description()],
                            resolve($this->command)->jobs()
                        )
                    );
                }

                public function getSignature(string $signature): string
                {
                    return "<code>{$signature}</code>";
                }

                public function getDescription(string $description): array|string|null
                {
                    return preg_replace('/`(.*)`/', '<code>$1</code>', $description);
                }
            };
        };

        $daily = $table(DailyCron::class);
        $monthly = $table(MonthlyCron::class);

        return view('admin.settings.scheduled_tasks', compact('daily', 'monthly'));
    }


    public function generateSitemap(SiteMapGenerator $service): void
    {
        try {
            $service->run();
            Message::success('Sikeres sitemap generálás');
            redirect_route('admin.release_notes');
        } catch (Throwable $e) {
            Message::danger('Sikertelen sitemap generálás.');
            report($e);
        }
    }
}
