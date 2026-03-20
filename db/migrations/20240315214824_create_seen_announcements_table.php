<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateSeenAnnouncementsTable extends AppMigration
{
    public function change(): void
    {
        $this->table('seen_announcements')
            ->addColumn('user_id', 'integer')
            ->addColumn('announcement_id', 'integer')
            ->datetime('seen_at', ['null' => true, 'default' => null])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('announcement_id', 'pages', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
