<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddDocumentToGroupsView extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $this->table('groups')
            ->addColumn('document', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->save();

        $this->execute(\App\Services\RefreshGroupViewTable::getQuery());
    }

    public function down()
    {
        $this->table('groups')
            ->removeColumn('document')
            ->save();
    }
}
