<?php

namespace App\Admin\Controllers\Api;

use App\Services\Statistics\EventLogAggregator;
use Framework\Http\Message;

class SyncStatisticsController
{
    public function __invoke(EventLogAggregator $aggregator)
    {
        $aggregator->run();
        Message::success('Sikeres frissítés');
        return redirect_route('admin.statistics');
    }
}