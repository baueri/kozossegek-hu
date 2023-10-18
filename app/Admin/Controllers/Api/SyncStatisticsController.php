<?php

declare(strict_types=1);

namespace App\Admin\Controllers\Api;

use App\Services\Statistics\EventLogAggregator;
use Framework\Http\Message;

class SyncStatisticsController
{
    public function __invoke(EventLogAggregator $aggregator): void
    {
        $aggregator->run();
        Message::success('Sikeres frissítés');
        redirect_route('admin.statistics');
    }
}