<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ReplaceInstituteFullTextIndex extends AbstractMigration
{
    public function up(): void
    {
        $this->table('institutes')
            ->removeIndex(['name', 'city'])
            ->addIndex(['name', 'city', 'district'], ['type' => 'fulltext', 'name' => 'search'])
            ->save();

    }
}
