<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class CreateManagedChurchGroupsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('managed_church_groups')
            ->addColumn('group_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addColumn('user_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addForeignKey('group_id', 'church_groups', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['user_id', 'group_id'], ['unique' => true])
            ->create();
    }

    public function down()
    {
        $this->table('managed_church_groups')->drop()->save();
    }
}
