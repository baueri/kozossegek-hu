<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class AddUpdatedAtToUserSessionsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('user_sessions')
            ->updatedAt()
            ->save();
    }

    public function down()
    {
        $this->table('user_sessions')
            ->removeColumn('updated_at')
            ->save();
    }
}
