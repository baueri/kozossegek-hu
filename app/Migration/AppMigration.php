<?php

namespace App\Migration;

class AppMigration extends \Phinx\Migration\AbstractMigration
{
    /**
     *
     * @param string $tableName
     * @param array $options
     * @return AppMigrationTable
     */
    public function table($tableName, $options = array()): AppMigrationTable
    {
        $table = new AppMigrationTable($tableName, $options, $this->getAdapter());
        $this->tables[] = $table;

        return $table;
    }
    
}
