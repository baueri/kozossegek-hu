<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddTagsToGroupsTable extends AbstractMigration
{

    public function up(): void
    {
        $tags = collect(explode(PHP_EOL, file_get_contents(ROOT . 'db/sources/tags.txt')))->filter()->all();
        $this->table('groups')
            ->addColumn('tags', MysqlAdapter::PHINX_TYPE_SET, ['values' => $tags])
            ->save();

        $this->table('group_tags')->drop()->save();
    }

    public function down()
    {
        $this->table('groups')
            ->removeColumn('tags')
            ->save();

        $this->table('group_tags')->create();
    }
}
