<?php

declare(strict_types=1);

final class CreateSeenAnnouncementsTable extends \App\Migration\AppMigration
{
    public function change(): void
    {
        $this->table('seen_announcements')
            ->addColumn('user_id', 'integer')
            ->addColumn('announcement_id', 'integer')
            ->createdAt('seen_at')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->addForeignKey('announcement_id', 'pages', 'id', ['delete' => 'CASCADE', 'update' => 'CASCADE'])
            ->create();
    }
}
