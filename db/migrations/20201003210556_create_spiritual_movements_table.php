<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateSpiritualMovementsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('spiritual_movements')
            ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('slug', MysqlAdapter::PHINX_TYPE_STRING)

            ->addIndex('name', ['unique' => true])
            ->addIndex('slug', ['unique' => true])
            ->create();
    }

    public function down()
    {
        $this->table('spiritual_movements')
            ->drop()
            ->save();
    }
}
