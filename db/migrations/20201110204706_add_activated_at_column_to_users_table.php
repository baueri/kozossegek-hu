<?php
declare(strict_types=1);

final class AddActivatedAtColumnToUsersTable extends \App\Migration\AppMigration
{
    public function up(): void
    {
        $this->table('users')
            ->timestamp('activated_at')
            ->save();
    }

    public function down()
    {
        $this->table('users')
            ->removeColumn('activated_at')
            ->save();
    }
}
