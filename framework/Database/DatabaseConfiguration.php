<?php

namespace Framework\Database;

class DatabaseConfiguration
{
    public function __construct(public readonly string $host,
        public readonly string $user,
        public readonly string $password,
        public readonly string $database,
        public readonly string $charset,
        public readonly int|string $port
    ) {
    }

    public function __debugInfo()
    {
        $result = get_object_vars($this);
        $result['password'] = '***';
        return $result;
    }
}
