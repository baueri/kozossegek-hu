<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddHighlightedColumnToSpiritualMovementsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('spiritual_movements')
            ->addColumn('highlighted', MysqlAdapter::PHINX_TYPE_BOOLEAN, ['default' => 0])
            ->save();
    }
}
