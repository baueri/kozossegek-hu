<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ReplaceInstituteFullTextIndex2 extends AbstractMigration
{
    public function up()
    {
//        $this->table('institutes')
//            ->removeIndexByName('search')
//            ->addIndex(['name', 'name2', 'city', 'district'], ['type' => 'fulltext', 'name' => 'search'])
//            ->addIndex('name', ['type' => 'fulltext', 'name' => 'search_name'])
//            ->addIndex('city', ['type' => 'fulltext', 'name' => 'search_city'])
//            ->update();
    }

    public function down()
    {

    }
}
