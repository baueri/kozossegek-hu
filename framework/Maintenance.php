<?php

namespace Framework;

class Maintenance
{
    const FILENAME = ROOT . '.maintenance';

    public function down(): void
    {
        if (!file_exists(self::FILENAME)) {
            touch(self::FILENAME);
        }
    }

    public function up(): void
    {
        if (file_exists(self::FILENAME)) {
            unlink(self::FILENAME);
        }
    }

    public function isMaintenanceOn(): bool
    {
        return file_exists(self::FILENAME);
    }
}
