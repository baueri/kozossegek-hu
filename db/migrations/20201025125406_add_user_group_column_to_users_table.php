<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddUserGroupColumnToUsersTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('users')
                ->addColumn('user_group', \Phinx\Db\Adapter\MysqlAdapter::PHINX_TYPE_STRING, ['default' => 'GROUP_LEADER'])
                ->addIndex('user_group')
                ->save();
    }
    
    public function down()
    {
        $this->table('users')
                ->removeColumn('user_group')
                ->save();
    }
}
