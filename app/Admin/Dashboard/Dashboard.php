<?php

declare(strict_types=1);

namespace App\Admin\Dashboard;

use App\Admin\Settings\Services\ErrorLogParser;
use App\QueryBuilders\ChurchGroups;
use Carbon\Carbon;

class Dashboard
{
    private ChurchGroups $groups;

    private ErrorLogParser $errorLogParser;

    public function __construct(ChurchGroups $groups, ErrorLogParser $errorLogParser)
    {
        $this->groups = $groups;
        $this->errorLogParser = $errorLogParser;
    }

    final public function getHtml(): string
    {
        $releaseInfo = $this->releaseNoteInfo();

        $groupStatsForThisMonth = $this->getGroupStats(date('Y-m-01'));

        $groupsThisMonth = $this->groups->query()
            ->where('created_at', '>=', date('Y-m-01'))
            ->notDeleted()
            ->count();

        $groupsTotal = $this->groups->query()->notDeleted()->count();
        $pendingGroups = $this->groups->query()->where('pending', 1)->notDeleted()->count();

        $lastError = $this->errorLogParser->getLastError();

        return view('admin.dashboard', [
            'search_count_this_month' => $groupStatsForThisMonth['search_count'],
            'group_open_count_this_month' => $groupStatsForThisMonth['group_open_count'],
            'group_contact_count' => $groupStatsForThisMonth['group_contact_count'],
            'groups_count' => $groupsTotal,
            'pending_groups' => $pendingGroups,
            'groups_this_month' => $groupsThisMonth,
            'last_error' => $lastError,
            'release_info' => $releaseInfo
        ]);
    }

    public function __toString(): string
    {
        return $this->getHtml();
    }

    private function getGroupStats(?string $from = null): array
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

        return $query->first();
    }

    private function releaseNoteInfo(): ?array
    {
        $page = file_get_contents(VIEWS . 'admin/release_notes.php');

        preg_match('/<h3>(.*)<\/h3>/', $page, $matches);

        if (!$matches) {
            return null;
        }

        $header = $matches[1];
        [, $date] = explode(' ', $header);

        $date = str_replace(['(', ')', '.'], ['', '', '-'], $date);

        $latestDate = Carbon::parse($date);
        $toCheck = now()->subDays(5);

        if ($toCheck->gt($latestDate)) {
            return null;
        }

        preg_match('~<ul>(.*?)</ul>~s', $page, $notes);

        return [
            'header' => $header,
            'notes' => $notes[0]
        ];
    }
}
