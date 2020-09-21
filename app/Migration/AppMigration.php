<?php

namespace App\Migration;

class AppMigration extends \Phinx\Migration\AbstractMigration
{
    /**
     * 
     * @param type $tableName
     * @param type $options
     * @return AppMigrationTable
     */
    public function table($tableName, $options = array()): \Phinx\Db\Table
    {
        $table = new AppMigrationTable($tableName, $options, $this->getAdapter());
        $this->tables[] = $table;

        return $table;
    }
    
}
