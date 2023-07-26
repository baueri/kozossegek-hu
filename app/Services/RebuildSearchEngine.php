<?php

namespace App\Services;

use App\Models\ChurchGroupView;
use App\QueryBuilders\ChurchGroupViews;

class RebuildSearchEngine
{
    public function __construct(
        private ChurchGroupViews $groupRepo
    ) {
    }

    public function run(): void
    {
        db()->execute('TRUNCATE search_engine');

        $this->groupRepo->each(fn(ChurchGroupView $groupView) => $this->updateSearchEngine($groupView));
    }

    public function updateSearchEngine(ChurchGroupView $group): void
    {
        $collector = new SearchEngineKeywordCollector($group);

        db()->execute(
            'replace into search_engine (group_id, keywords) values(?, ?)',
            $group->getId(),
            $collector->getKeywords()->implode(' ')
        );
    }
}
