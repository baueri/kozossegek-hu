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

class SearchGroupService
{

    /**
     * @var GroupViews
     */
    private $groupRepo;

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

        $this->logEvent($filter);

        return $groups;
    }

    private function logEvent($data)
    {
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        EventDisptatcher::dispatch(new SearchTriggered('search', $data));
    }
}
