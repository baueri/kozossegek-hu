<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\AdapterInterface;

final class SetConfirmedAtColumnsDefaultValueToNull extends AppMigration
{
    public function up(): void
    {
        $this->table('church_groups')
            ->changeColumn(
                'confirmed_at',
                AdapterInterface::PHINX_TYPE_DATETIME,
                ['default' => null, 'after' => 'notified_at', 'null' => true]
            )->save();
    }
}
