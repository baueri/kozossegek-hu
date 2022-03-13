<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddMarkerColumnToOsmMarkersTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('osm_markers')
            ->addColumn('marker', AdapterInterface::PHINX_TYPE_STRING, ['default' => get_site_url() . '/images/marker_red.png'])
            ->save();
    }
}
