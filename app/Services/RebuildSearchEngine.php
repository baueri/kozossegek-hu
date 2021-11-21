<?php

namespace App\Services;

use App\Models\GroupView;
use App\QueryBuilders\GroupViews;
use App\Repositories\Groups;
use App\Repositories\Institutes;
use Legacy\Group;

class RebuildSearchEngine
{
    private GroupViews $groupViews;

    private Institutes $institutes;

    private Groups $groupRepo;

    public function __construct(Groups $groupRepo, Institutes $institutes, GroupViews $groupViews)
    {
        $this->groupRepo = $groupRepo;
        $this->institutes = $institutes;
        $this->groupViews = $groupViews;
    }

    public function run()
    {
        db()->execute('delete search_engine from search_engine
            left join church_groups cg on search_engine.group_id = cg.id
            where cg.id is null or cg.deleted_at is not null');

        foreach ($this->groupRepo->all() as $group) {
            $this->updateSearchEngine($group);
        }
    }

    public function updateSearchEngine(Group $group)
    {
        /* @var $groupView GroupView */
        $groupView = $this->groupViews->query()->find($group->id);
        $keywords = collect(builder('v_group_tags')->where('group_id', $groupView->id)->get())->pluck('tag_name');
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
            $groupView->id,
            $keywords->implode(' ')
        );
    }
}
