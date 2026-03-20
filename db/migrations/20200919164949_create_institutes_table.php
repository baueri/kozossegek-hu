<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;

final class CreateInstitutesTable extends \App\Migration\AppMigration
{

    public function up(): void
    {
        $this->table('institutes')
                ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING, ['comment' => 'intézmény/plébánia neve'])
                ->addColumn('city', MysqlAdapter::PHINX_TYPE_STRING)
                ->addColumn('address', MysqlAdapter::PHINX_TYPE_STRING)
                ->addColumn('leader_name', MysqlAdapter::PHINX_TYPE_STRING, ['comment' => 'Intézményvezető/plébános neve'])
                ->datetimes()
                ->addIndex('name')
                ->addIndex('city')
                ->addIndex(['name', 'city'])
                ->create();
    }
    
    public function down()
    {
        $this->table('institutes')->drop()->save();
    }
}
