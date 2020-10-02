<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

use Phinx\Db\Adapter\MysqlAdapter;

final class AddUserIdToInstitutesTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('institutes')
            ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->save();
    }

    public function down()
    {
        $this->table('institutes')->removeColumn('user_id')->save();
    }
}
