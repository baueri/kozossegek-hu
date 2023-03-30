<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddConfirmedAtColumnToChurchGroupsColumn extends AppMigration
{
    public function up(): void
    {
        $this->table('church_groups')
            ->timestamp('confirmed_at', ['default' => 'CURRENT_TIMESTAMP', 'after' => 'notified_at'])
            ->changeColumn('updated_at', AdapterInterface::PHINX_TYPE_DATETIME, ['null' => true])
            ->save();

        db()->execute('update church_groups set confirmed_at=updated_at');
    }

    public function down()
    {
        $this->table('church_groups')
            ->removeColumn('confirmed_at')
            ->changeColumn('updated_at', AdapterInterface::PHINX_TYPE_DATETIME, ['null' => true, 'update' => 'CURRENT_TIMESTAMP'])
            ->save();
    }
}
