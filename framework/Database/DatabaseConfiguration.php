<?php


namespace Framework\Database;


class DatabaseConfiguration
{
    public $host;

    public $user;

    public $password;

    public $database;

    public $charset;

    public function __construct($host = null, $user = null, $password = null, $database = null, $charset = null)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;
    }
}