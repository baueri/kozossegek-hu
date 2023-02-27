<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class AddSpiritualMovementType extends AppMigration
{
    public function up(): void
    {
        $this->table('spiritual_movements')
            ->enum('type', ['spiritual_movement', 'monastic_community'])
            ->save();
    }

    public function down()
    {
        $this->table('spiritual_movements')
            ->removeColumn('type')
            ->save();
    }
}
