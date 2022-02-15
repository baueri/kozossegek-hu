<?php

namespace App\Services;

use App\Models\ChurchGroupView;
use App\QueryBuilders\GroupViews;
use App\Repositories\Groups;
use Legacy\Group;

class RebuildSearchEngine
{
    public function __construct(private Groups $groupRepo, private GroupViews $groupViews)
    {
    }

    public function run(): void
    {
        db()->execute('delete search_engine from search_engine
            left join church_groups cg on search_engine.group_id = cg.id
            where cg.id is null or cg.deleted_at is not null');

        foreach ($this->groupRepo->all() as $group) {
            $this->updateSearchEngine($group);
        }
    }

    public function updateSearchEngine(Group $group): void
    {
        $groupView = $this->getGroupView($group);
        $keywords = collect(builder('v_group_tags')->where('group_id', $groupView->getId())->get())->pluck('tag_name');
        $keywords[] = $groupView->denomination();
        $keywords = $keywords->merge($groupView->getAgeGroups())
            ->merge($groupView->getDays())
            ->push($groupView->occasionFrequency())
            ->push($groupView->city)
            ->push(str_replace('atya', '', $groupView->leader_name))
            ->push($groupView->group_leaders)
            ->push($groupView->institute_name)
            ->push($groupView->institute_name2);

        if ($groupView->spiritual_movement) {
            $keywords->push($groupView->spiritual_movement);
        }

        if ($groupView->district) {
            $keywords->push($groupView->district);
        }

        $keywords = $keywords->merge(collect(explode(' ', $groupView->name))->filter(function ($word) {
            return strlen($word) >= 3;
        }));

        db()->execute(
            'replace into search_engine (group_id, keywords) values(?, ?)',
            $groupView->getId(),
            $keywords->implode(' ')
        );
    }

    private function getGroupView(Group $group): ChurchGroupView
    {
        return $this->groupViews->query()->find($group->getId());
    }
}
