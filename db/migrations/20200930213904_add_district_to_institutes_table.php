<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddDistrictToInstitutesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('institutes')
            ->addColumn('district', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true, 'comment' => 'városrész'])
            ->addIndex('district')
            ->save();
    }

    public function down()
    {
        $this->table('institutes')
            ->removeColumn('district')
            ->save();
    }
}
