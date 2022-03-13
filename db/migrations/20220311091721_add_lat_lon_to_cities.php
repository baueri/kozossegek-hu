<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddLatLonToCities extends AbstractMigration
{
    public function up(): void
    {
        $this->table('cities')
            ->addColumn('lat', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->addColumn('lon', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->save();
    }
}
