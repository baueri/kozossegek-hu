<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddLatLonToInstitutesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('institutes')
            ->addColumn('lat', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->addColumn('lon', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->save();
    }
}
