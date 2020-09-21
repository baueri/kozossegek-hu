<?php


namespace Framework\Database\PDO;


use Framework\Database\Database;
use Framework\Database\DatabaseConfiguration;
use Framework\Database\ResultSet;
use PDO;

class PDOMysqlDatabase implements Database
{

    /**
     * @var DatabaseConfiguration
     */
    private $configuration;

    /**
     * @var PDO
     */
    private $pdo;

    /**
     * PDOMysqlDatabase constructor.
     * @param DatabaseConfiguration $configuration
     */
    public function __construct(DatabaseConfiguration $configuration)
    {
        $this->configuration = $configuration;

        $this->pdo = new PDO($this->getDsn(), $this->configuration->user, $this->configuration->password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    private function getDsn()
    {
        return 'mysql:host=' . $this->configuration->host . ';dbname=' . $this->configuration->database . ';charset=' . $this->configuration->charset;
    }

    /**
     * @param DatabaseConfiguration $configuration
     * @return static
     */
    public static function connect(DatabaseConfiguration $configuration)
    {
        return new static($configuration);
    }

    public function execute($query, ...$params): ResultSet
    {
        $statement = $this->pdo->prepare($query);

        $statement->execute($params);

        return new PDOResultSet($statement);
    }

    /**
     * @param $query
     * @param mixed ...$bindings
     * @return array
     */
    public function select($query, $bindings = []): array
    {
        return $this->execute($query, ...$bindings)->getRows();
    }

    /**
     * @param $query
     * @param array $params
     * @return int
     */
    public function update($query, ...$params): int
    {
        return $this->execute($query, ...$params)->rowCount();
    }

    /**
     * @return int|null
     */
    public function lastInsertId(): ?int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @param $query
     * @param mixed ...$bindings
     * @return array
     */
    public function first($query, $bindings = [])
    {
        return $this->execute($query, ...$bindings)->fetchRow();
    }

    /**
     * @return PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }

    /**
     * @param $query
     * @param mixed ...$params
     * @return int
     */
    public function insert($query, $params = []): int
    {
        $this->execute($query, ...$params);

        return $this->lastInsertId();
    }

    public function exists($query, $params = []): bool
    {
        return (bool) $this->first($query, $params);
    }
}