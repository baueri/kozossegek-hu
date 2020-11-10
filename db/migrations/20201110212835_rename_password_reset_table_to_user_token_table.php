<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class RenamePasswordResetTableToUserTokenTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('password_reset')
            ->addColumn('page', MysqlAdapter::PHINX_TYPE_STRING)
            ->rename('user_token')
            ->save();

    }
}
