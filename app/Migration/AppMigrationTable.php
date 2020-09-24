<?php

namespace App\Migration;

class AppMigrationTable extends \Phinx\Db\Table
{
    /**
     * 
     * @param bool $withDeletedAt
     * @return static
     */
    public function timestamps(bool $withDeletedAt = true)
    {
        $this->timestamp('created_at', ['default' => 'CURRENT_TIMESTAMP']);
        $this->timestamp('updated_at', ['null' => true, 'update' => 'CURRENT_TIMESTAMP']);
        
        if ($withDeletedAt) {
            $this->timestamp('deleted_at', ['null' => true]);
        }
         
        return $this;
                
    }
    
    /**
     * 
     * @param string $columName
     * @param array $options
     * @return static
     */
    public function timestamp(string $columName, array $options = [''])
    {
        
        $this->addColumn($columName, \Phinx\Db\Adapter\MysqlAdapter::PHINX_TYPE_DATETIME, $options);
        
        return $this;
    }
}
