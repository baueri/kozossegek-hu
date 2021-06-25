<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateNotificationsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('notifications')
            ->addColumn('title', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('message', MysqlAdapter::PHINX_TYPE_TEXT)
            ->addColumn('display_for', MysqlAdapter::PHINX_TYPE_ENUM, ['default' => 'PORTAL', 'values' => ['PORTAL', 'ADMIN']])
            ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->createdAt()
            ->updatedAt()
            ->addIndex('user_id')
            ->create();
    }

    public function down(): void
    {
        $this->table('notifications')
            ->drop()
            ->save();
    }
}
