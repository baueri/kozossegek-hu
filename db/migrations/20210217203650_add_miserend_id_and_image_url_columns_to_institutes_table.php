<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddMiserendIdAndImageUrlColumnsToInstitutesTable extends AbstractMigration
{
    public function up()
    {
        $this->table('institutes')
            ->addColumn('miserend_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true])
            ->addColumn('image_url', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->addIndex('miserend_id')
            ->save();
    }

    public function down()
    {
        $this->table('institutes')
            ->removeColumn('miserend_id')
            ->removeColumn('image_url')
            ->save();
    }
}
