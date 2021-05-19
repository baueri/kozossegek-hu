<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChangeEventLogColumnName extends AbstractMigration
{
    public function up(): void
    {
        $this->table('event_logs')
            ->renameColumn('data', 'log')
            ->save();
    }

    public function down()
    {
        $this->table('event_logs')
            ->renameColumn('log', 'data')
            ->save();
    }
}
