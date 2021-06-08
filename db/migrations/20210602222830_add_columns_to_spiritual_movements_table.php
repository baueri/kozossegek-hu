<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddColumnsToSpiritualMovementsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('spiritual_movements')
            ->addColumn('description', MysqlAdapter::PHINX_TYPE_TEXT, ['null' => true])
            ->addColumn('image_url', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->addColumn('website', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->save();
    }

    public function down(): void
    {
        $this->table('spiritual_movements')
            ->removeColumn('description')
            ->removeColumn('image_url')
            ->removeColumn('website')
            ->save();
    }
}
