<?php

namespace App\Portal\Services;

use App\Events\SearchTriggered;
use App\Models\ChurchGroupView;
use App\Services\GroupSearchRepository;
use Framework\Event\EventDisptatcher;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class SearchGroupService
{
    private GroupSearchRepository $groupRepo;

    private CrawlerDetect $crawlerDetect;

    public function __construct(GroupSearchRepository $groupRepo, CrawlerDetect $crawlerDetect)
    {
        $this->groupRepo = $groupRepo;
        $this->crawlerDetect = $crawlerDetect;
    }

    /**
     * @param array|Collection $filter
     * @param int $perPage
     * @return PaginatedModelCollection<ChurchGroupView>
     */
    public function search(array|Collection $filter, int $perPage = 21): PaginatedModelCollection
    {
        $groups = $this->groupRepo->search($filter, $perPage);

        $this->logEvent($filter);

        return $groups;
    }

    private function logEvent($data)
    {
        if (isset($data['status'])) {
            unset($data['status']);
        }

        if (isset($data['pending'])) {
            unset($data['pending']);
        }

        if ($this->shouldLog($data)) {
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data['ref'] = request()->get('ref');
            EventDisptatcher::dispatch(new SearchTriggered('search', $data));
        }
    }

    private function shouldLog($filterData): bool
    {
        return !$this->crawlerDetect->isCrawler() &&
            array_filter($filterData) &&
            request()->get('pg', 1) == 1;
    }
}
