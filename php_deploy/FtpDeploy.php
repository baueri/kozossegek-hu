<?php

class FtpDeploy
{
    private $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    private function connect() {
        $connection = ftp_connect($configuration['domain'], $configuration['port'] ?? 22);

        if (!$connection) {
            throw new \Exception('Could not connect to ftp');
        }

        return $connection;
    }

    private function close($connection) {
        ftp_close($connection);
    }

    private function login()
    {
        $connection = $this->connect();


    }

    public function touch($filename)
    {
        print_r($filename);
        exit(1);
    }

    public function __destruct()
    {

    }
}
