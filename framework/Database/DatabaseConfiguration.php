<?php


namespace Framework\Database;


class DatabaseConfiguration
{
    public $host;

    public $user;

    public $password;

    public $database;

    public $charset;

    public $port;

    /**
     * DatabaseConfiguration constructor.
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $database
     * @param string $charset
     * @param int $port
     */
    public function __construct($host = null, $user = null, $password = null, $database = null, $charset = null, $port = null)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->charset = $charset;
        $this->port = $port;
    }
}