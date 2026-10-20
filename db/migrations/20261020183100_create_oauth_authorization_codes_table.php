<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateOauthAuthorizationCodesTable extends AppMigration
{
    public function up(): void
    {
        $this->table('oauth_authorization_codes')
            ->string('code_hash', ['length' => 64])
            ->string('client_id', ['length' => 64])
            ->integer('user_id')
            ->string('redirect_uri', ['length' => 500])
            ->string('code_challenge', ['length' => 128])
            ->string('code_challenge_method', ['length' => 10, 'default' => 'S256'])
            ->datetime('expires_at')
            ->datetime('used_at', ['null' => true])
            ->datetimes(false)
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->addIndex('code_hash', ['unique' => true])
            ->addIndex(['client_id', 'user_id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('oauth_authorization_codes')->drop()->save();
    }
}
