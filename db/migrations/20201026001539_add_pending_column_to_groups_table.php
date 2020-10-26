<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPendingColumnToGroupsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
                ->addColumn('pending', \Phinx\Db\Adapter\MysqlAdapter::PHINX_TYPE_INTEGER, ['length' => 1, 'default' => 1])
                ->addIndex('pending')
                ->save();
        
        $this->execute('update groups set pending=1 WHERE status = "' . App\Enums\GroupStatusEnum::PENDING . '"');
        
        $this->execute(\App\Services\RefreshGroupViewTable::getQuery());
    }
    
    public function down()
    {
        $this->table('groups')->removeColumn('pending')->save();
    }
}
