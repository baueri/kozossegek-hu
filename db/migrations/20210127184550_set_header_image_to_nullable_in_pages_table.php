<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class SetHeaderImageToNullableInPagesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('pages')
            ->changeColumn('header_image', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->save();
    }

    public function down()
    {

    }
}
