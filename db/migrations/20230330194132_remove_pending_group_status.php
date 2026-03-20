<?php

declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\AdapterInterface;

final class RemovePendingGroupStatus extends AppMigration
{
    public function up(): void
    {
        $this->execute("UPDATE church_groups SET status = 'active' WHERE status = 'pending'");

        $this->table('church_groups')
            ->changeColumn('status', AdapterInterface::PHINX_TYPE_ENUM, ['values' => ['active', 'inactive']])
            ->save();
    }
}
