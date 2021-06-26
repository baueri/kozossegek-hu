<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class RenameDisplayForColumnInNotificationsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('notifications')
            ->removeColumn('display_for')
            ->addColumn('category', MysqlAdapter::PHINX_TYPE_ENUM, [
                'values' => [
                    'LEGAL_NOTICE',
                    'NEWS',
                    'ADMIN_NOTIFICATION'
                ],
                'default' => 'LEGAL_NOTICE'
            ])
            ->save();
    }

    public function down(): void
    {
        $this->table('notifications')
            ->removeColumn('category')
            ->addColumn(
                'display_for',
                MysqlAdapter::PHINX_TYPE_ENUM,
                [
                    'after' => 'user_id',
                    'default' => 'PORTAL',
                    'values' => ['PORTAL', 'ADMIN']
                ],
            )
            ->save();
    }
}
