<?php

namespace Framework;

class Maintenance
{

    const FILENAME = ROOT . '.maintenance';

    public function down()
    {
        if (!file_exists(self::FILENAME)) {
            touch(self::FILENAME);
        }
    }

    public function up()
    {
        if (file_exists(self::FILENAME)) {
            unlink(self::FILENAME);
        }
    }

    public function isMaintenanceOn()
    {
        return file_exists(self::FILENAME);
    }
}
