<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateEventTagsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('event_tags')
            ->addColumn('event_id', 'integer')
            ->addColumn('tag', 'string')

            ->addIndex(['tag'])
            ->addForeignKey(['event_id'], 'events', options: ['delete' => 'cascade'])
            ->create();
    }
}
