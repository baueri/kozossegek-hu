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
        $this->timestamp('created_at', ['default' => 'CURRENT_TIMESTAMP']);
        $this->timestamp('updated_at', ['null' => true, 'update' => 'CURRENT_TIMESTAMP']);

        if ($withDeletedAt) {
            $this->timestamp('deleted_at', ['null' => true]);
        }

        return $this;

    }

    /**
     *
     * @param string $columnName
     * @param array $options
     * @return static
     */
    public function timestamp(string $columnName, array $options = [''])
    {
        $this->addColumn($columnName, MysqlAdapter::PHINX_TYPE_DATETIME, $options);

        return $this;
    }
}
