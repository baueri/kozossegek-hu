<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateUserLegalNoticesTable extends AppMigration
{
    public function up(): void
    {
        $this->table('user_legal_notices')
            ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->addColumn('accepted_legal_notice_version', MysqlAdapter::PHINX_TYPE_SMALL_INTEGER, ['default' => 1])
            ->timestamp('accepted_at', ['update' => 'CURRENT_TIMESTAMP', 'default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->save();
    }

    public function down()
    {
        $this->table('user_legal_notices')
            ->drop()
            ->save();
    }
}
