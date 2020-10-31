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
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ]);
    }

    private function getDsn()
    {
        return 'mysql:host=' . $this->configuration->host . ';dbname=' . $this->configuration->database . ';charset=' . $this->configuration->charset . ';port=' . $this->configuration->port;
    }

    /**
     * @param DatabaseConfiguration $configuration
     * @return static
     */
    public static function connect(DatabaseConfiguration $configuration)
    {
        return new static($configuration);
    }

    /**
     *
     * @param type $query
     * @param type $params
     * @return ResultSet
     */
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

    public function fetchColumn($query, $params = [])
    {
        $row = $this->first($query, $params);

        return array_shift($row);
    }

    public function delete($query, $params = [])
    {
        $this->execute($query, ...$params);

        return true;
    }

}
