<?php

declare(strict_types=1);

namespace App\Console;

use App\Console\Commands\AggregateLogsCommand;
use App\Console\Commands\ClearUserSessionCommand;
use App\Console\Commands\Cron\DailyCron;
use App\Console\Commands\Cron\MonthlyCron;
use App\Console\Commands\GeneratePassword;
use App\Console\Commands\GroupActivityConfirmNotifier;
use App\Console\Commands\InactivateUnconfirmedGroups;
use App\Console\Commands\Install;
use App\Console\Commands\PublishApp;
use App\Console\Commands\RebuildSearchEngineCommand;
use App\Console\Commands\SetLatLonToInstitutes;
use App\Console\Commands\SiteMapGenerator;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use Framework\Console\ConsoleKernel as Kernel;

class ConsoleKernel extends Kernel
{
    protected array $commands = [
        Install::class,
        DailyCron::class,
        MonthlyCron::class,
        ClearUserSessionCommand::class,
        RebuildSearchEngineCommand::class,
        PublishApp::class,
        AggregateLogsCommand::class,
        SiteMapGenerator::class,
        OpenStreetMapSync::class,
        SetLatLonToInstitutes::class,
        GroupActivityConfirmNotifier::class,
        InactivateUnconfirmedGroups::class,
        GeneratePassword::class
    ];
}
