<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddFulltextIndexOnInstitutesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('institutes')
            ->addIndex(['name', 'city'], ['type' => 'fulltext'])
            ->save();
    }
}
