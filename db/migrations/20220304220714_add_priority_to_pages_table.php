<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddPriorityToPagesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('pages')
            ->addColumn('priority', AdapterInterface::PHINX_TYPE_STRING, ['default' => '0.5'])
            ->save();
    }
}
