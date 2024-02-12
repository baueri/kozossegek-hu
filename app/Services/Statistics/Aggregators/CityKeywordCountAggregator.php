<?php

namespace App\Services\Statistics\Aggregators;

use App\Enums\AgeGroup;
use App\Enums\EventType;
use App\Enums\Tag;

class CityKeywordCountAggregator extends StatAggregator
{
    private array $aggregated = [];

    public function add(array $row): void
    {
        $city = $this->getCity($row);
        if ($row['type'] !== EventType::search->name) {
            return;
        }
        $this->addAgeGroup($city, $row);
        $this->addTags($city, $row);
        $this->addKeywords($city, $row);

    }

    private function addAgeGroup(string $city, array $row)
    {
        $ageGroup = AgeGroup::tryFrom($row['log']['korosztaly'] ?? '')?->translate();
        if ($ageGroup) {
            $this->increase($city, $ageGroup, 'age_group');
        }
    }

    private function addTags(string $city, array $row): void
    {
        Tag::fromList($row['log']['tags'] ?? '', ',')->each(function (Tag $tag) use ($city) {
            $this->increase($city, $tag->translate(), 'tag');
        });
    }

    private function addKeywords(string $city, array $row)
    {
        $words = array_filter(explode(' ', $row['log']['search'] ?? ''));
        if ($words) {
            array_walk($words, fn ($keyword) => $this->increase($city, trim($keyword, '.+?*'), 'keyword'));
        }
    }

    public function save(): int
    {
        if (!$this->aggregated) {
            return 0;
        }
        return collect($this->aggregated)
            ->chunk(100)
            ->map(function (array $aggregated) {
                $values = $bindings = [];
                $columns = implode(',', array_keys($aggregated[0]));
                foreach ($aggregated as $row) {
                    $values[] = '(' . implode(',', array_fill(0, count($row), '?')) . ')';
                    $bindings = array_merge($bindings, array_values($row));
                }

                $valuesStr = implode(', ', $values);

                $table = 'stat_city_keywords';

                return db()->execute(<<<SQL
                    insert into {$table} ({$columns})
                    values {$valuesStr}
                    on duplicate key update cnt = {$table}.cnt + VALUES(cnt)
                SQL, ...$bindings)->rowCount();
        })->sum();
    }

    private function increase(string $city, string $keyword, string $type)
    {
        $keyword = mb_strtolower($keyword);

        if (ucfirst($keyword) === $city) {
            return;
        }

        $composite = "{$city}_{$keyword}_{$type}";
        if (!isset($this->aggregated[$composite])) {
            $this->aggregated[$composite] = ['city' => $city, 'keyword' => $keyword, 'cnt' => 0, 'type' => $type];
        }

        $this->aggregated[$composite]['cnt']++;
    }
}