<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use App\Migration\AppMigration;

final class AddLastLoginToUsersTable extends AppMigration
{

    public function up(): void
    {
        $this->table('users')
            ->timestamp('last_login', ['null' => true])
            ->save();
    }

    public function down()
    {
        $this->table('users')
            ->removeColumn('last_login')
            ->save();
    }
}
