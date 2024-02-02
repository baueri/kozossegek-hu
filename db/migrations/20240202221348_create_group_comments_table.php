<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateGroupCommentsTable extends AppMigration
{
    public function change(): void
    {
        $this->table('group_comments')
            ->integer('group_id')
            ->text('comment')
            ->integer('last_comment_user')
            ->datetime('commented_at', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('group_id', 'church_groups', options: ['delete' => 'CASCADE'])
            ->addForeignKey('last_comment_user', 'users', options: ['delete' => 'CASCADE'])
            ->unique('group_id')
            ->create();
    }
}
