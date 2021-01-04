<?php

class SFtpDeploy
{
    private array $configuration;

    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return resource
     * @throws Exception
     */
    private function connect()
    {
        $connection = ssh2_connect($this->configuration['domain'], $this->configuration['port'] ?? 22);
        var_dump($connection);exit;
        if (!$connection) {
            throw new \Exception('Could not connect to ftp');
        }

        return $connection;
    }

    private function close($connection)
    {
        ftp_close($connection);
    }

    private function login()
    {
        $connection = $this->connect();
    }

    /**
     * @param $filename
     * @throws Exception
     */
    public function touch($filename)
    {
        $this->connect();
        print_r($filename);
        exit(1);
    }

    public function __destruct()
    {
    }
}
