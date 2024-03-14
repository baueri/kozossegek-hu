<?php

declare(strict_types=1);

namespace Framework;

readonly class Maintenance
{
    public string $file;

    public function __construct()
    {
        $this->file = app()->root('.maintenance');
    }

    public function down(): void
    {
        if (!file_exists($this->file)) {
            touch($this->file);
        }
    }

    public function up(): void
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    public function isMaintenanceOn(): bool
    {
        return file_exists($this->file);
    }
}
