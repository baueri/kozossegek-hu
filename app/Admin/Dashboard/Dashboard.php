<?php

namespace App\Admin\Dashboard;

use App\Admin\Settings\Services\ErrorLogParser;
use App\Repositories\Groups;

class Dashboard
{
    private Groups $groups;

    private ErrorLogParser $errorLogParser;

    public function __construct(Groups $groups, ErrorLogParser $errorLogParser)
    {
        $this->groups = $groups;
        $this->errorLogParser = $errorLogParser;
    }

    final public function getHtml(): string
    {
        $groupStatsForThisMonth = $this->getGroupStats(date('Y-m-01'));

        $groupsThisMonth = $this->groups->query()
            ->where('created_at', '>=', date('Y-m-01'))
            ->apply('notDeleted')->count();

        $groupsTotal = $this->groups->query()->apply('notDeleted')->count();
        $pendingGroups = $this->groups->query()->where('pending', 1)->apply('notDeleted')->count();

        $lastError = $this->errorLogParser->getLastError();

        return view('admin.dashboard', [
            'search_count_this_month' => $groupStatsForThisMonth['search_count'],
            'group_open_count_this_month' => $groupStatsForThisMonth['group_open_count'],
            'group_contact_count' => $groupStatsForThisMonth['group_contact_count'],
            'groups_count' => $groupsTotal,
            'pending_groups' => $pendingGroups,
            'groups_this_month' => $groupsThisMonth,
            'last_error' => $lastError
        ]);
    }

    public function __toString(): string
    {
        return $this->getHtml();
    }

    private function getGroupStats(?string $from = null, ?string $to = null): array
    {
        $query = builder('event_logs')
            ->select(
                'sum(type="search") as search_count,
                 sum(type="group_profile_opened") as group_open_count,
                 sum(type="group_contact") as group_contact_count'
            );

        if ($from) {
            $query->where('created_at', '>=', $from);
        }

        if ($to) {
            $query->where('created_at', '<=', $to);
        }

        return $query->first();
    }
}
