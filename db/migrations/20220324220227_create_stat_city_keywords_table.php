<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class CreateStatCityKeywordsTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('stat_city_keywords')
            ->addColumn('city', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('keyword', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('type', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('cnt', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addIndex(['city', 'keyword'], ['unique' => true])
            ->addIndex('type')
            ->create();
    }
}
