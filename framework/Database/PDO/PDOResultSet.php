<?php


namespace Framework\Database\PDO;


use Framework\Database\ResultSet;
use PDO;
use PDOStatement;

class PDOResultSet implements ResultSet
{

    const FETCH_STYLE = PDO::FETCH_ASSOC;

    /**
     * @var PDOStatement
     */
    private $statement;

    public function __construct(PDOStatement $statement)
    {

        $this->statement = $statement;
    }

    public function fetchRow($fetchStyle = self::FETCH_STYLE)
    {
        return $this->statement->fetch($fetchStyle) ?: null;
    }

    public function getRows($fetchStyle = self::FETCH_STYLE)
    {
        return $this->statement->fetchAll($fetchStyle);
    }

    public function rowCount()
    {
        return $this->statement->rowCount();
    }
}
