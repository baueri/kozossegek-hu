<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddApprovedColumnToInstitutesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('institutes')
            ->addColumn('approved', \Phinx\Db\Adapter\MysqlAdapter::PHINX_TYPE_BOOLEAN, ['default' => 1])
            ->save();
    }

    public function down()
    {
        $this->table('institutes')
            ->removeColumn('approved')
            ->save();
    }
}
