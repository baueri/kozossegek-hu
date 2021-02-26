<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUserSessionsViewTable extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('CREATE OR REPLACE VIEW v_user_sessions AS
            SELECT users.*, user_sessions.unique_id FROM user_sessions, users where user_sessions.user_id=users.id
        ');
    }

    public function down()
    {
        $this->execute('DROP VIEW v_user_sessions');
    }
}
