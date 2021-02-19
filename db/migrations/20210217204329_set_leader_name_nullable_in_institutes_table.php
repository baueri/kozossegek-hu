<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class SetLeaderNameNullableInInstitutesTable extends AbstractMigration
{
    public function up()
    {
        $this->table('institutes')
            ->changeColumn('leader_name', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->changeColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true, 'default' => 0])
            ->save();
    }

    public function down()
    {
    }
}
