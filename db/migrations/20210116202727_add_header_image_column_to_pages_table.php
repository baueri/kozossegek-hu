<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddHeaderImageColumnToPagesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('pages')
            ->addColumn('header_image', MysqlAdapter::PHINX_TYPE_STRING)
            ->save();
    }

    public function down()
    {
        $this->table('pages')
            ->removeColumn('header_image')
            ->save();
    }
}
