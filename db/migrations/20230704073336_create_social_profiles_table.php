<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateSocialProfilesTable extends AppMigration
{
    public function change(): void
    {
        $this->table('social_profile')
            ->integer('user_id')
            ->enum('social_provider', ['facebook', 'google'])
            ->string('social_id')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addIndex(['user_id', 'social_provider'])
            ->create();
    }
}
