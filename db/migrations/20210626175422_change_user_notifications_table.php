<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class ChangeUserNotificationsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('user_notifications')
            ->timestamp('accepted_at', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addColumn(
                'category',
                MysqlAdapter::PHINX_TYPE_ENUM,
                [
                    'values' => [
                        'LEGAL_NOTICE',
                        'NEWS',
                        'ADMIN_NOTIFICATION'
                    ]
                ]
            )
            ->addIndex(['user_id', 'category'], ['unique' => true])
            ->save();
    }

    public function down(): void
    {
        $this->table('user_notifications')
            ->removeColumn('category')
            ->removeColumn('accepted_at')
            ->save();
    }
}
