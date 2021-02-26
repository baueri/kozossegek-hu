<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveGroupTableIfExists extends AbstractMigration
{
    public function up(): void
    {
        if (($table = $this->table('group'))->exists()) {
            $table->drop()->save();
        }
    }

    public function down()
    {

    }
}
