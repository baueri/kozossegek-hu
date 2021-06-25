<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateUserNotificationsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('user_notifications')
            ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->addColumn('notification_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->timestamp('created_at', ['default' => 'CURRENT_TIMESTAMP', 'comment' => 'Mikor lett elfogadva'])
            ->addForeignKey('user_id', 'users')
            ->addForeignKey('notification_id', 'notifications')
            ->create();
    }

    public function down(): void
    {
        $this->table('user_notifications')
            ->drop()
            ->save();
    }
}
