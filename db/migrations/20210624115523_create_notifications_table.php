<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateNotificationsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('notifications')
            ->addColumn('title', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('message', MysqlAdapter::PHINX_TYPE_TEXT)
            ->addColumn('display_for', MysqlAdapter::PHINX_TYPE_ENUM, ['default' => 'PORTAL', 'values' => ['PORTAL', 'ADMIN']])
            ->create();
    }

    public function down(): void
    {
        $this->table('notifications')
            ->drop()
            ->save();
    }
}
