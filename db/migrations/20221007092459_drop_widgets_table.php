<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class DropWidgetsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('widgets')
            ->drop()
            ->save();
    }
}
