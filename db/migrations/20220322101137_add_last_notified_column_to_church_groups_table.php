<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class AddLastNotifiedColumnToChurchGroupsTable extends AppMigration
{
    public function change(): void
    {
        $this->table('church_groups')
            ->datetime('notified_at', ['default' => null, 'null' => true, 'after' => 'deleted_at'])
            ->save();
    }
}
