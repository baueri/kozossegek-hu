<?php
declare(strict_types=1);

use App\Enums\PageStatus;
use App\Migration\AppMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreatePagesTable extends AppMigration
{

    public function up(): void
    {
        $this->table('pages')
            ->addColumn('title', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('slug', MysqlAdapter::PHINX_TYPE_STRING)
            ->addColumn('content', MysqlAdapter::PHINX_TYPE_TEXT)
            ->addColumn('user_id', MysqlAdapter::PHINX_TYPE_INTEGER)
            ->addColumn('status', MysqlAdapter::PHINX_TYPE_ENUM, ['values' => PageStatus::asArray(), 'default' => PageStatus::PUBLISHED])
            ->timestamps()
            ->create();
    }
}
