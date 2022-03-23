<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class AddLastNotifiedColumnToChurchGroupsTable extends AppMigration
{
    public function change(): void
    {
        $this->table('church_groups')
            ->timestamp('notified_at', ['default' => null, 'null' => true, 'after' => 'deleted_at'])
            ->save();

        db()->update('update church_groups set notified_at=created_at');
    }
}
