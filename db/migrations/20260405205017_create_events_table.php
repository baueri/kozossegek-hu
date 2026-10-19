<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateEventsTable extends AppMigration
{
    public function change(): void
    {
        $this->table('events')
            ->addColumn('name', 'string')
            ->addColumn('slug', 'string', ['null' => true])

            ->addColumn('description', 'text', ['null' => true])

            ->addColumn('starts_at', 'datetime')
            ->addColumn('ends_at', 'datetime', ['null' => true])
            ->addColumn('all_day', 'boolean', ['default' => false])

            ->addColumn('status', 'enum', [
                'values' => ['draft', 'pending', 'approved', 'rejected'],
                'default' => 'draft'
            ])
            ->addColumn('lifecycle', 'enum', [
                'values' => ['active', 'cancelled'],
                'default' => 'active'
            ])

            ->addColumn('organizer', 'string', ['null' => true])

            ->addColumn('institute_id', 'integer', ['null' => true])
            ->addColumn('user_id', 'integer')

            ->addColumn('location_name', 'string', ['null' => true])
            ->addColumn('address', 'string', ['null' => true])
            ->addColumn('lat', 'decimal', ['precision' => 10, 'scale' => 7, 'null' => true])
            ->addColumn('lng', 'decimal', ['precision' => 10, 'scale' => 7, 'null' => true])

            ->addColumn('featured_image', 'string', ['null' => true])

            ->addTimestamps()

            ->addIndex(['starts_at'])
            ->addIndex(['status'])
            ->addIndex(['lifecycle'])
            ->unique(['slug'])
            ->addForeignKey(['institute_id'], 'institutes', options: ['delete' => 'set null'])
            ->addForeignKey(['user_id'], 'users', options: ['delete' => 'cascade'])
            ->create();
    }
}
