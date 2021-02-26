<?php

declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class SetUpdatedAtNullableInUsersTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('users')
            ->changeColumn(
                'activated_at',
                MysqlAdapter::PHINX_TYPE_DATETIME,
                ['null' => true]
            )
        ->update();
    }

    public function down()
    {
    }
}
