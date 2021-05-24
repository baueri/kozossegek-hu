<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use App\Services\RefreshGroupViewTable;
use Phinx\Db\Adapter\MysqlAdapter;

final class UpdateAgeGroupSet extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
            ->changeColumn('age_group', MysqlAdapter::PHINX_TYPE_SET, ['values' => App\Enums\AgeGroupEnum::asArray(), 'comment' => 'korosztály'])
            ->save();

        $this->execute(RefreshGroupViewTable::getQuery());
    }

    public function down()
    {

    }

}
