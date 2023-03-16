<?php
declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Table\Column;

final class CreateThirdPartyCredentialsTable extends AppMigration
{
    public function up(): void
    {
        $this->table('third_party_credentials')
            ->addColumn('app_name', Column::STRING, ['length' => 100])
            ->addColumn('app_secret', Column::STRING, ['length' => 255])
            ->addColumn('user_id', Column::INTEGER, ['length' => 11])
            ->timestamps()
            ->addIndex('app_name', ['unique' => true])
            ->addIndex('user_id')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }

    public function down()
    {
        $this->table('third_party_credentials')
            ->drop()
            ->save();
    }
}
