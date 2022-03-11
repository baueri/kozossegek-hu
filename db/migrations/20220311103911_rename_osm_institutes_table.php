<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameOsmInstitutesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('osm_institutes')
            ->rename('osm_markers')
            ->save();
    }
}
