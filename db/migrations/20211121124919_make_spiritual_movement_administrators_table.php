<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class MakeSpiritualMovementAdministratorsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('spiritual_movement_administrators')
            ->addColumn('user_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addColumn('spiritual_movement_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addForeignKey('user_id', 'users')
            ->addForeignKey('spiritual_movement_id', 'spiritual_movements')
            ->addIndex('user_id', ['unique' => true])
            ->create();
    }

    public function down(): void
    {
        $this->table('spiritual_movement_administrators')->drop()->save();
    }
}
