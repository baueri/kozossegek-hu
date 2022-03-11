<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddTypeToOsmCityTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('osm_institutes')
            ->addColumn('type', AdapterInterface::PHINX_TYPE_STRING, ['length' => 10])
            ->dropForeignKey(['institute_id'])
            ->removeColumn('institute_id')
            ->save();
    }
}
