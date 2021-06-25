<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddPhoneNumberColumnToUsersTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('users')
            ->addColumn('phone_number', MysqlAdapter::PHINX_TYPE_STRING, ['null' => true])
            ->save();

        $this->execute('update users left join church_groups on users.id = church_groups.user_id
            set users.phone_number = church_groups.group_leader_phone
            where church_groups.id is not null');
    }

    public function down()
    {
        $this->execute('update church_groups left join users on users.id = church_groups.user_id
            set church_groups.group_leader_phone = users.phone_number
            where users.id is not null');

        $this->table('users')
            ->removeColumn('phone_number')
            ->save();
    }
}
