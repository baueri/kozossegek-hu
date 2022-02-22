<?php

namespace App\Services;

use App\Models\ChurchGroupView;
use App\QueryBuilders\GroupViews;

class RebuildSearchEngine
{
    public function __construct(
        private GroupViews $groupRepo
    ) {
    }

    public function run(): void
    {
        db()->execute('delete search_engine from search_engine
            left join church_groups cg on search_engine.group_id = cg.id
            where cg.id is null or cg.deleted_at is not null');

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
