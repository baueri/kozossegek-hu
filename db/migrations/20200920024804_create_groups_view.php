<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupsView extends AbstractMigration
{

    public function up(): void
    {
        $this->execute('CREATE OR REPLACE VIEW v_groups AS
            SELECT groups.*, institutes.name as institute_name, institutes.leader_name
            FROM groups 
            LEFT JOIN institutes ON groups.institute_id=institutes.id
            GROUP BY groups.id');
    }
    
    public function down()
    {
        $this->execute('DROP VIEW v_groups');
    }
}
