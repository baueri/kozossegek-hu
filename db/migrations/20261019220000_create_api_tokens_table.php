<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateApiTokensTable extends AppMigration
{
    public function up(): void
    {
        $this->table('api_tokens')
            ->integer('user_id')
            ->string('name', ['length' => 100])
            ->string('token_prefix', ['length' => 12])
            ->string('token_hash', ['length' => 64])
            ->datetime('last_used_at', ['null' => true])
            ->datetimes(false)
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addIndex('token_hash', ['unique' => true])
            ->addIndex('user_id')
            ->create();
    }

    public function down(): void
    {
        $this->table('api_tokens')->drop()->save();
    }
}
