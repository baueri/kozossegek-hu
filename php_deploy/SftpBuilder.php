<?php

namespace PHPDeploy;

use phpseclib3\Net\SFTP;

class SftpBuilder
{
    public function build(array $conf): SFTP
    {
        $sftp = new SFTP($conf['domain'], $conf['port'] ?? 22, $conf['timeout'] ?? 10);

        $sftp->login($conf['user'], $conf['password']);

        return $sftp;
    }
}
