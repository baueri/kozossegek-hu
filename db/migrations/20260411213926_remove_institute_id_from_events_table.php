<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class RemoveInstituteIdFromEventsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('events')
            ->dropForeignKey(['institute_id'])
            ->save();

        $this->table('events')
            ->removeColumn('institute_id')
            ->save();
    }

    public function down(): void
    {
        $this->table('events')
            ->addColumn('institute_id', 'integer', ['null' => true, 'after' => 'organizer'])
            ->addForeignKey(['institute_id'], 'institutes', options: ['delete' => 'set null'])
            ->save();
    }
}
