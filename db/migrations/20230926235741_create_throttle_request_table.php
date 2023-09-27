<?php
declare(strict_types=1);

use App\Migration\AppMigration;

final class CreateThrottleRequestTable extends AppMigration
{
    public function change(): void
    {
        $this->table('throttle_request')
            ->string('ip', ['limit' => 15])
            ->string('user_agent', ['limit' => 255])
            ->integer('count')
            ->datetime('created_at')
            ->datetime('expires_at', ['null' => true])
            ->create();
    }
}
