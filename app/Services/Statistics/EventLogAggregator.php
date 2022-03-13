<?php

namespace App\Services\Statistics;

use App\Repositories\CityStatistics;
use App\Services\Statistics\Aggregators\CityStatAggregator;
use Framework\Database\Builder;
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
        $lastAggregated = CityStatistics::query()->select('max(date)')->fetchFirst();
        builder('event_logs')
            ->when($lastAggregated, fn (Builder $query) => $query->where('date(created_at)', '>', $lastAggregated))
            ->orderBy('id')
            ->each(function (array $row) use (&$uas) {
                $row['log'] = json_decode($row['log'], true);
                if ($this->crawlerDetect->isCrawler($row['log']['user_agent'] ?? null)) {
                    return;
                }
                $this->cityStatAggregator->add($row);
            }, 100);
        return $this->cityStatAggregator->save();
    }
}
