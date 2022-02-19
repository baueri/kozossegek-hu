<?php

declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;
use App\Enums\DenominationEnum;
use App\Enums\AgeGroup;

final class CreateGroupsTable extends AppMigration
{
    public function up(): void
    {
         $this->table('groups')
                 ->addColumn('name', MysqlAdapter::PHINX_TYPE_STRING)
                 ->addColumn('description', MysqlAdapter::PHINX_TYPE_TEXT, ['null' => true, 'length' => MysqlAdapter::TEXT_LONG])
                 ->addColumn('city', MysqlAdapter::PHINX_TYPE_STRING)
                 ->addColumn('denomination', MysqlAdapter::PHINX_TYPE_ENUM, ['values' => DenominationEnum::asArray(), 'default' => DenominationEnum::KATOLIKUS, 'comment' => 'felekezet'])
                 ->addColumn('institute_id', MysqlAdapter::PHINX_TYPE_INTEGER, ['null' => true])
                 ->addColumn('group_leaders', MysqlAdapter::PHINX_TYPE_STRING)
                 ->addColumn('group_leader_email', MysqlAdapter::PHINX_TYPE_STRING)
                 ->addColumn('group_leader_phone', MysqlAdapter::PHINX_TYPE_STRING)
                 ->addColumn('spiritual_movement', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true, 'comment' => 'lelkiségi mozgalom'])
                 ->addColumn('age_group', MysqlAdapter::PHINX_TYPE_ENUM, ['values' => AgeGroup::toArray(), 'comment' => 'korosztály'])
                 ->addColumn('occasion_frequency', MysqlAdapter::PHINX_TYPE_ENUM, ['values' => App\Enums\OccasionFrequencyEnum::asArray(), 'comment' => 'milyen gyakran találkoznak a közösségek'])
                 ->addColumn('status', MysqlAdapter::PHINX_TYPE_ENUM, ['values' => App\Enums\GroupStatusEnum::asArray()])
                 ->timestamps()
                 ->addIndex('name')
                 ->addIndex('city')
                 ->addIndex('denomination')
                 ->addIndex('institute_id')
                 ->addIndex(['city', 'name'])
                 ->addIndex('spiritual_movement')
                 ->addIndex('age_group')
                 ->addIndex('occasion_frequency')
                 ->addIndex('status')
                 ->create();
    }
    
    public function down()
    {
        $this->table('groups')->drop()->save();
    }
}
