<?php

namespace App\Portal\Services;

use App\Events\SearchTriggered;
use App\Models\ChurchGroupView;
use App\Services\GroupSearchRepository;
use Framework\Event\EventDisptatcher;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;

class SearchGroupService
{
    private GroupSearchRepository $groupRepo;

    public function __construct(GroupSearchRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    /**
     * @param array|Collection $filter
     * @param int $perPage
     * @return PaginatedModelCollection<ChurchGroupView>
     */
    public function search(array|Collection $filter, int $perPage = 21): PaginatedModelCollection
    {
        $groups = $this->groupRepo->search($filter, $perPage);

        EventDisptatcher::dispatch(new SearchTriggered('search', $filter));

        return $groups;
    }
}
