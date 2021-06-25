<?php

namespace App\Portal\Services;

use App\Events\SearchTriggered;
use App\Repositories\GroupViews;
use Framework\Database\PaginatedResultSet;
use Framework\Event\EventDisptatcher;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class SearchGroupService
{

    /**
     * @var GroupViews
     */
    private GroupViews $groupRepo;

    /**
     *
     * @param GroupViews $groupRepo
     */
    public function __construct(GroupViews $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     *
     * @param Collection|array $filter
     * @param int $perPage
     * @return PaginatedResultSet|Model[]|ModelCollection|PaginatedModelCollection
     */
    public function search($filter, $perPage = 21)
    {
        $groups = $this->groupRepo->search($filter, $perPage);

        if (!app(CrawlerDetect::class)->isCrawler()) {
            $this->logEvent($filter);
        }

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

        if (self::shouldLog($data)) {
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            EventDisptatcher::dispatch(new SearchTriggered('search', $data));
        }
    }

    private static function shouldLog($filterData)
    {
        return (bool) array_filter($filterData);
    }
}
