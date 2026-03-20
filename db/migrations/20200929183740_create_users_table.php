<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateUsersTable extends AppMigration
{
    public function up(): void
    {
        $this->table('users')
            ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('username', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->addColumn('email', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('password', MysqlAdapter::PHINX_TYPE_STRING, ['length' => 255])
            ->datetimes()

            ->addIndex('username', ['unique' => true])
            ->addIndex('email', ['unique' => true])

            ->create();
    }

    public function down()
    {
        $this->table('users')->drop()->save();
    }
}
