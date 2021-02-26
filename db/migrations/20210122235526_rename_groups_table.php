<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameGroupsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('groups')
            ->rename('church_groups')
            ->save();

        $this->execute(\App\Services\RefreshGroupViewTable::getQuery());
    }

    public function down()
    {
        $this->table('church_groups')
            ->rename('groups')
            ->save();
    }
}
