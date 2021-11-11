<?php

namespace Framework\Database;

interface ResultSet
{
    /**
     * @return array|object
     */
    public function fetchRow();

    public function getRows(): array;

    public function rowCount(): int;
}
