<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddInstituteIdForeignKeyToChurchGroupsTable extends AbstractMigration
{
    public function change(): void
    {
        db()->execute(<<<SQL
            UPDATE church_groups SET institute_id=NULL WHERE institute_id=0
        SQL);

        $this->table('church_groups')
            ->addForeignKey('institute_id', 'institutes', 'id')
            ->save();
    }
}
