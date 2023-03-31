<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateUserSessionsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('user_sessions')
            ->addColumn('unique_id', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->datetime('created_at', ['default' => 'CURRENT_TIMESTAMP'])
            ->save();
    }

    public function down()
    {
        $this->table('user_sessions')->drop()->save();
    }
}
