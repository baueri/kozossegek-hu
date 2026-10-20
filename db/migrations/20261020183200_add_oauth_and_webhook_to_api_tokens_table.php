<?php

declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\AdapterInterface;

final class AddOauthAndWebhookToApiTokensTable extends AppMigration
{
    public function up(): void
    {
        $this->table('api_tokens')
            ->addColumn('client_id', AdapterInterface::PHINX_TYPE_STRING, [
                'length' => 64,
                'null' => true,
                'after' => 'user_id',
            ])
            ->addColumn('grant_type', AdapterInterface::PHINX_TYPE_STRING, [
                'length' => 16,
                'default' => 'pat',
                'after' => 'client_id',
            ])
            ->addColumn('webhook_url', AdapterInterface::PHINX_TYPE_STRING, [
                'length' => 500,
                'null' => true,
                'after' => 'last_used_at',
            ])
            ->addColumn('webhook_secret', AdapterInterface::PHINX_TYPE_STRING, [
                'length' => 255,
                'null' => true,
                'after' => 'webhook_url',
            ])
            ->addColumn('webhook_registered_at', AdapterInterface::PHINX_TYPE_DATETIME, [
                'null' => true,
                'after' => 'webhook_secret',
            ])
            ->addIndex('client_id')
            ->addIndex('grant_type')
            ->update();
    }

    public function down(): void
    {
        $table = $this->table('api_tokens');
        foreach (['webhook_registered_at', 'webhook_secret', 'webhook_url', 'grant_type', 'client_id'] as $column) {
            if ($table->hasColumn($column)) {
                $table->removeColumn($column);
            }
        }
        $table->update();
    }
}
