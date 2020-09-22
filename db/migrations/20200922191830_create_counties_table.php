<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateCountiesTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('counties')
            ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('country_code', MysqlAdapter::PHINX_TYPE_STRING, ['length' => 2])
            ->addIndex('name')
            ->addIndex('country_code')
            ->create();
    }
    
    public function down()
    {
        $this->table('counties')->drop()->save();
    }
}
