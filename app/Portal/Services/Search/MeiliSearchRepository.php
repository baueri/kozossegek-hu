<?php

declare(strict_types=1);

namespace App\Portal\Services\Search;

use App\Services\MeiliSearch\MeiliSearchAdapter;
use Framework\Database\PaginatedResultSet;
use Framework\Database\PaginatedResultSetInterface;

class MeiliSearchRepository implements SearchRepository
{
    public function __construct(
        protected readonly MeiliSearchAdapter $adapter
    ) {
    }

    public function search(array $filter, int $perPage = 21): PaginatedResultSetInterface
    {
        $params = ['matchingStrategy' => 'all', 'hitsPerPage' => $perPage, 'page' => (int) request()->get('pg') ?: 1];
        $keyword = $filter['search'] ?? null;
        $results = $this->adapter->index->search($keyword, $params);

        return new PaginatedResultSet($results->getHits(), $results->getHitsPerPage(), $results->getPage(), $results->getTotalHits());
    }
}
