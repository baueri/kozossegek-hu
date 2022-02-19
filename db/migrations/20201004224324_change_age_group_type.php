<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class ChangeAgeGroupType extends AbstractMigration
{

    public function up(): void
    {
        $this->table('groups')
            ->changeColumn('age_group', MysqlAdapter::PHINX_TYPE_SET, ['values' => App\Enums\AgeGroup::toArray(), 'comment' => 'korosztály'])
            ->save();
    }
}
