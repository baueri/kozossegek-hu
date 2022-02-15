<?php

namespace Framework\Database;

use Closure;

interface Database
{
    public function execute(string $query, ...$bindings): ResultSet;

    public function select(string $query, array $bindings = []): array;

    public function first(string $query, $bindings = []);

    public function update($query, ...$params): int;

    /**
     * @return int|string
     */
    public function insert(string $query, array $params = []);

    public function exists($query, $params = []): bool;

    /**
     * @return int number of affected rows
     */
    public function delete(string $query, array $params = []): int;

    public function fetchColumn($query, $params = []);

    /**
     * @return mixed
     */
    public function lastInsertId();

    public function beginTransaction(): bool;

    public function commit(): bool;

    public function rollback(): bool;

    public function transaction(Closure $callback);
}
