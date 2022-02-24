<?php

namespace App\Services\Statistics;

use App\Services\Statistics\Aggregators\CityStatAggregator;

class EventLogAggregator
{
    public function __construct(
        private readonly CityStatAggregator $cityStatAggregator
    ) {
    }

    public function handle()
    {
        builder('event_logs')
            ->each(function (array $row) {
                $row['log'] = json_decode($row['log'], true);
                $this->cityStatAggregator->add($row);
            });

        $this->cityStatAggregator->save();
    }
}