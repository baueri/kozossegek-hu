<?php

declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddPageTypeToPagesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('pages')
            ->addColumn('page_type', AdapterInterface::PHINX_TYPE_ENUM, ['values' => ['page', 'announcement']])
            ->update();
    }
}
