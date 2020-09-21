<?php


namespace Framework\Database;


interface ResultSet
{
    public function fetchRow();

    public function getRows();

    public function rowCount();
}