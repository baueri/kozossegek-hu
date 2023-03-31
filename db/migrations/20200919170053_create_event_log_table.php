<?php
declare(strict_types=1);


use Phinx\Db\Adapter\MysqlAdapter;

final class CreateEventLogTable extends \App\Migration\AppMigration
{

    public function up(): void
    {
        $this->table('event_logs')
                ->addColumn('type', MysqlAdapter::PHINX_TYPE_STRING)
                ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['default' => '0'])
                ->addColumn('data', MysqlAdapter::PHINX_TYPE_TEXT, ['null' => true])
                ->datetime('created_at', ['default' => 'CURRENT_TIMESTAMP'])
                ->addIndex('type')
                ->addIndex('user_id')
                ->create();
    }
    
    public function down()
    {
        $this->table('event_logs')->drop()->save();
    }
}
