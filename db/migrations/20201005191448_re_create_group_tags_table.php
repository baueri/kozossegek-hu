<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use App\Services\RefreshGroupViewTable;

final class ReCreateGroupTagsTable extends AbstractMigration
{

    public function up(): void
    {
        $this->table('group_tags')
            ->addColumn('group_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->addColumn('tag', MysqlAdapter::PHINX_TYPE_STRING)
            ->addIndex('group_id')
            ->addIndex('tag')
            ->addIndex(['group_id', 'tag'], ['unique' => true])
            ->create();

        $groupTags = collect(builder('groups')->select('id as group_id, tags')->where('tags', '<>', '')->get())->keyBy('group_id');

        foreach ($groupTags as $group_id => $tags) {
            foreach(explode(',', $tags['tags']) as $tag) {
                $this->table('group_tags')->insert(compact('group_id', 'tag'))->save();
            }
        }

        $this->table('groups')
            ->removeColumn('tags')->save();

        $this->execute(RefreshGroupViewTable::getQuery());
    }

    public function down()
    {
        $this->table('group_tags')->drop()->save();
    }
}
