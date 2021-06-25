<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class SetGroupLeaderEmailNullableInChurchGroupsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('church_groups')
            ->changeColumn(
                'group_leader_email',
                MysqlAdapter::PHINX_TYPE_STRING,
                ['null' => true]
            )->save();
    }
}
