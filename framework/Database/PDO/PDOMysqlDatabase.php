<?php

declare(strict_types=1);

namespace Framework\Database\PDO;

use BackedEnum;
use Closure;
use Exception;
use Framework\Database\Database;
use Framework\Database\DatabaseException;
use Framework\Database\Events\QueryRan;
use Framework\Database\ResultSet;
use Framework\Event\EventDisptatcher;
use PDO;
use UnitEnum;

class PDOMysqlDatabase implements Database
{
    private int $transactionCounter = 0;

    public function __construct(public readonly PDO $pdo) {}

    public function execute(string $query, ...$bindings): ResultSet
    {
        $start = microtime(true);

        $bindings = $this->prepareBindings($bindings);

        $statement = $this->pdo->prepare($query);

        $statement->execute($bindings);

        $time = microtime(true) - $start;

        EventDisptatcher::dispatch(new QueryRan($query, $bindings, $time));

        return new PDOResultSet($statement);
    }

    public function select(string $query, array $bindings = []): array
    {
        return $this->execute($query, ...$bindings)->getRows();
    }

    public function beginTransaction(): bool
    {
        if (!$this->transactionCounter++) {
            return $this->pdo->beginTransaction();
        }
        $this->pdo->exec('SAVEPOINT trans' . $this->transactionCounter);
        return $this->transactionCounter >= 0;
    }

    public function commit(): bool
    {
        if (!--$this->transactionCounter) {
            return $this->pdo->commit();
        }
        return $this->transactionCounter >= 0;
    }

    public function rollback(): bool
    {
        if (--$this->transactionCounter) {
            $this->pdo->exec('ROLLBACK TO trans' . ($this->transactionCounter + 1));
            return true;
        }

        return $this->pdo->rollBack();
    }

    /**
     * @throws Exception
     */
    public function transaction(Closure $callback): mixed
    {
        $this->beginTransaction();

        try {
            $return = $callback();
            $this->commit();
            return $return;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function update($query, ...$params): int
    {
        return $this->execute($query, ...$params)->rowCount();
    }

    public function lastInsertId(): bool|string
    {
        return $this->pdo->lastInsertId();
    }

    public function first(string $query, $bindings = []): object|array|null
    {
        return $this->execute($query, ...$bindings)->fetchRow();
    }

    /**
     * @throws DatabaseException
     */
    public function value(string $query, array $bindings = [])
    {
        $row = $this->first($query, $bindings);

        preg_match('/^select (.*),? from/i', $query, $matches);

        if (!$matches) {
            throw new DatabaseException('could not fetch column name from query: ' . $query);
        }

        return ((array) $row)[$matches[1]] ?? null;
    }

    public function insert(string $query, array $params = []): string
    {
        $this->execute($query, ...$params);

        return $this->lastInsertId();
    }

    public function exists($query, $params = []): bool
    {
        return (bool) $this->first($query, $params);
    }

    public function fetchColumn($query, $params = [])
    {
        $row = $this->first($query, $params);

        return array_shift($row);
    }

    public function delete(string $query, array $params = []): int
    {
        return $this->execute($query, ...$params)->rowCount();
    }

    private function prepareBindings(array $bindings): array
    {
        return array_map(function($binding) {
            if ($binding instanceof UnitEnum) {
                return $binding instanceof BackedEnum ? $binding->value : $binding->name;
            }
            return $binding;
        }, $bindings);
    }
}
