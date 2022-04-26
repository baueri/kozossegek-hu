<?php

namespace App\Services\Statistics;

use App\Repositories\CityStatistics;
use App\Services\Statistics\Aggregators\CityKeywordCountAggregator;
use App\Services\Statistics\Aggregators\CityStatAggregator;
use App\Services\Statistics\Aggregators\StatAggregator;
use Framework\Database\Builder;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class EventLogAggregator
{
    private array $aggregators = [
        CityStatAggregator::class,
        CityKeywordCountAggregator::class
    ];

    public function __construct(
        private readonly CrawlerDetect $crawlerDetect
    ) {
    }

    public function run(): int
    {
        $aggregators = array_map(fn ($aggregator): StatAggregator => resolve($aggregator), $this->aggregators);

        $lastAggregated = CityStatistics::query()->select('max(date)')->fetchFirst();
        builder('event_logs')
            ->when($lastAggregated, fn (Builder $query) => $query->where('date(created_at)', '>', $lastAggregated))
            ->orderBy('id')
            ->each(function (array $row) use ($aggregators) {
                $row['log'] = json_decode($row['log'], true);
                if ($this->crawlerDetect->isCrawler($row['log']['user_agent'] ?? null)) {
                    return;
                }
                array_walk($aggregators, fn (StatAggregator $aggregator) => $aggregator->add($row));
            }, 100);

        return array_sum(array_map(fn (StatAggregator $aggregator) => $aggregator->save(), $aggregators));
    }
}
