<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class CreateOsmInstituteTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('osm_institutes', ['id' => false, 'primary_key' => 'institute_id'])
            ->addColumn('institute_id', AdapterInterface::PHINX_TYPE_INTEGER)
            ->addColumn('latlon', AdapterInterface::PHINX_TYPE_STRING, ['length' => 100])
            ->addColumn('popup_html', AdapterInterface::PHINX_TYPE_TEXT)
            ->addForeignKey('institute_id', 'institutes', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
