<?php


namespace Framework\Database;


interface Database
{
    /**
     *
     * @param string $query
     * @param mixed ...$bindings
     * @return ResultSet
     */
    public function execute($query, ...$bindings): ResultSet;

    /**
     *
     * @param string $query
     * @param array $bindings
     * @return array []
     */
    public function select(string $query, $bindings = []): array;

    /**
     * @param string $query
     * @param array $bindings
     * @return mixed
     */
    public function first(string $query, $bindings = []);

    public function update($query, ...$params): int;

    public function insert($query, $params = []): int;

    public function exists($query, $params = []) :bool;

    public function delete($query, $params = []);

    public function fetchColumn($query, $params = []);

    public function lastInsertId(): ?int;

}
