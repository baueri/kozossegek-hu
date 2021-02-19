<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class SetGroupFieldsNullable extends AbstractMigration
{
    public function up()
    {
        $this->table('church_groups')
            ->changeColumn('group_leader_phone', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->addColumn('image_url', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->save();
    }

    public function down()
    {

    }
}
