<?php

namespace App\Portal\Services\Search;

use App\Events\SearchTriggered;
use App\Services\GroupSearchRepository;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Event\EventDisptatcher;

class DatabaseSearchGroupRepository implements SearchRepository
{
    private GroupSearchRepository $groupRepo;

    public function __construct(GroupSearchRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }

    public function search(array $filter, int $perPage = 21): PaginatedResultSetInterface
    {
        $groups = $this->groupRepo->search($filter)->paginate($perPage)->castInto('toSearchResult');

        EventDisptatcher::dispatch(new SearchTriggered('search', $filter));

        return $groups;
    }
}
