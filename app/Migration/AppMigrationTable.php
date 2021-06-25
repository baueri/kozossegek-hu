<?php

namespace App\Migration;

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Db\Table;

class AppMigrationTable extends Table
{
    /**
     *
     * @param bool $withDeletedAt
     * @return static
     */
    public function timestamps(bool $withDeletedAt = true)
    {
        $this->createdAt();
        $this->timestamp('updated_at', ['null' => true, 'update' => 'CURRENT_TIMESTAMP']);

        if ($withDeletedAt) {
            $this->deletedAt();
        }

        return $this;
    }

    public function createdAt(string $columnName = 'created_at')
    {
        $this->timestamp($columnName, ['default' => 'CURRENT_TIMESTAMP']);

        return $this;
    }

    /**
     *
     * @param string $columnName
     * @param array $options
     * @return static
     */
    public function timestamp(string $columnName, array $options = [])
    {
        $this->addColumn($columnName, MysqlAdapter::PHINX_TYPE_DATETIME, $options);

        return $this;
    }

    public function deletedAt()
    {
        $this->timestamp('deleted_at', ['null' => true]);

        return $this;
    }

    public function updatedAt(string $columnName = 'updated_at')
    {
        $this->timestamp($columnName, ['null' => true, 'update' => 'CURRENT_TIMESTAMP']);

        return $this;
    }
}
