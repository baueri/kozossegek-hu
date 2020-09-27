<?php


namespace Framework\Database;


interface Database
{
    /**
     * 
     * @param string $query
     * @return \Framework\Database\ResultSet
     */
    public function execute($query, ...$params): ResultSet;

    /**
     * 
     * @param string $query
     * @param [] $bindings
     * @return []
     */
    public function select($query, $bindings = []): array;

    /**
     * @param string $query
     * @param array $bindings
     * @return mixed
     */
    public function first($query, $bindings = []);

    public function update($query, ...$params): int;

    public function insert($query, $params = []): int;

    public function exists($query, $params = []) :bool;
    
    public function delete($query, $params = []);

    public function lastInsertId(): ?int;

}