<?php

declare(strict_types=1);

namespace App\Portal\Services\Search;

use Framework\Database\PaginatedResultSetInterface;

/**
 * @template T
 */
interface SearchRepository
{
    /**
     * @phpstan-return PaginatedResultSetInterface<T>
     */
    public function search(array $filter, int $perPage = 21): PaginatedResultSetInterface;
}
