<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreatePasswordResetTable extends App\Migration\AppMigration
{
    public function up(): void
    {
        $this->table('password_reset')
                ->addColumn('email', MysqlAdapter::PHINX_TYPE_STRING)
                ->addColumn('token', MysqlAdapter::PHINX_TYPE_STRING)
                ->timestamp('expires_at', ['default' => 'CURRENT_TIMESTAMP'])
                ->addIndex('token', ['unique' => true])
                ->save();
    }
    
    public function down()
    {
        $this->table('password_reset')
                ->drop()
                ->save();
    }
    
}
