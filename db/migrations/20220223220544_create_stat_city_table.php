<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\AdapterInterface;

final class CreateStatCityTable extends AppMigration
{
    public function up(): void
    {
        $this->table('stat_city')
            ->addColumn('city', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('search_count', AdapterInterface::PHINX_TYPE_INTEGER, ['default' => 0])
            ->addColumn('opened_groups_count', AdapterInterface::PHINX_TYPE_INTEGER, ['default' => 0])
            ->addColumn('contacted_groups_count', AdapterInterface::PHINX_TYPE_INTEGER, ['default' => 0])
            ->date('date')
            ->addIndex(['city', 'date'], ['name' => 'composite', 'unique' => true])
            ->create();
    }

    public function down()
    {
        $this->table('stat_city')
            ->drop()
            ->save();
    }
}
