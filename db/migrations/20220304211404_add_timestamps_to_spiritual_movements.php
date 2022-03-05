<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class AddTimestampsToSpiritualMovements extends AppMigration
{
    public function up(): void
    {
        $this->table('spiritual_movements')
            ->createdAt()
            ->updatedAt()
            ->save();
    }

    public function down()
    {
        $this->table('spiritual_movements')
            ->dropTimeStamps()
            ->save();
    }
}
