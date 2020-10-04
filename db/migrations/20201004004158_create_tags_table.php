<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateTagsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('tags')
            ->addColumn('tag', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('slug', MysqlAdapter::PHINX_TYPE_STRING)
            ->addIndex('tag', ['unique' => true])
            ->addIndex('slug', ['unique' => true])
            ->create();
    }

    public function down()
    {
        $this->table('tags')->drop()->save();
    }
}
