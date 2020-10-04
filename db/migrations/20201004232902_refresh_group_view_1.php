<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use App\Services\RefreshGroupViewTable;

final class RefreshGroupView1 extends AbstractMigration
{

    public function change(): void
    {
        $this->execute(RefreshGroupViewTable::getQuery());
    }
}
