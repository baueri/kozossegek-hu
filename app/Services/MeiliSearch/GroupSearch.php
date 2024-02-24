<?php

declare(strict_types=1);

namespace App\Services\MeiliSearch;

use Meilisearch\Search\SearchResult;

class GroupSearch
{
    public function __construct(
        protected readonly MeiliSearchAdapter $adapter
    ) {
    }

    public function search(string $keyword, array $params = [], ?int $limit = null): SearchResult
    {
        $params = array_merge(['matchingStrategy' => 'all'], $params);
        if ($limit) {
            $params['limit'] = $limit;
        }
        return $this->adapter->index->search($keyword, $params);
    }
}
