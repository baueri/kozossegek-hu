<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;

final class CreateCitiesTable extends App\Migration\AppMigration
{

    public function up(): void
    {
        $this->table('cities')
            ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('county_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->addColumn('country_code', MysqlAdapter::PHINX_TYPE_STRING, ['length' => 2])
            ->addIndex('name')
            ->addIndex('county_id')
            ->addIndex('country_code')
            ->create();
    }
    
    public function down()
    {
        $this->table('cities')->drop()->save();
    }
}
