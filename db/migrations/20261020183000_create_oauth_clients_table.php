<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateOauthClientsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('oauth_clients')
            ->string('client_id', ['length' => 64])
            ->string('name', ['length' => 150])
            ->string('client_secret_hash', ['length' => 64, 'null' => true])
            ->string('redirect_uri_policy', ['length' => 32, 'default' => 'https_any'])
            ->datetimes(false)
            ->addIndex('client_id', ['unique' => true])
            ->create();

        $this->table('oauth_clients')->insert([
            'client_id' => 'kozossegek-partner-sync',
            'name' => 'Partner alkalmazás',
            'client_secret_hash' => null,
            'redirect_uri_policy' => 'https_any',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => null,
        ])->saveData();
    }

    public function down(): void
    {
        $this->table('oauth_clients')->drop()->save();
    }
}
