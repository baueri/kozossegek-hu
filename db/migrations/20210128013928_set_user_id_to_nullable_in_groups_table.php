<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class SetUserIdToNullableInGroupsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('church_groups')
            ->changeColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true])
            ->save();
    }

    public function down()
    {

    }
}
