<?php

namespace App\Services\Statistics;

use App\Repositories\CityStatistics;
use App\Services\Statistics\Aggregators\CityStatAggregator;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class EventLogAggregator
{
    public function __construct(
        private readonly CityStatAggregator $cityStatAggregator,
        private readonly CrawlerDetect $crawlerDetect
    ) {
    }

    public function run(): int
    {
        builder('event_logs')
            ->whereRaw('date(created_at) > (select max(date) from ' . CityStatistics::query()->getTable() . ')')
            ->orderBy('id')
            ->each(function (array $row) use (&$uas) {
                $row['log'] = json_decode($row['log'], true);
                if ($this->crawlerDetect->isCrawler($row['log']['user_agent'] ?? null)) {
                    return;
                }
                $this->cityStatAggregator->add($row);
            });

        return $this->cityStatAggregator->save();
    }
}