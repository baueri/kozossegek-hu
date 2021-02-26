<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RefreshGroupViewTable extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(\App\Services\RefreshGroupViewTable::getQuery());
    }

    public function down()
    {

    }
}
