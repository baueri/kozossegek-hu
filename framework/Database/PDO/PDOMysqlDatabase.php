<?php

namespace Framework\Database\PDO;

use Closure;
use Exception;
use Framework\Database\Database;
use Framework\Database\DatabaseConfiguration;
use Framework\Database\Events\QueryRan;
use Framework\Database\ResultSet;
use Framework\Event\EventDisptatcher;
use PDO;

class PDOMysqlDatabase implements Database
{
    private int $transactionCounter = 0;

    public function __construct(private PDO $pdo) {}

    public function execute(string $query, ...$bindings): ResultSet
    {
        $start = microtime(true);

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

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function first(string $query, $bindings = [])
    {
        return $this->execute($query, ...$bindings)->fetchRow();
    }

    public function insert(string $query, array $params = [])
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
}
