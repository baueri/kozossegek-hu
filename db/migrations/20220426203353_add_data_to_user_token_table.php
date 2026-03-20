<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddDataToUserTokenTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('user_token')
            ->addColumn('data', AdapterInterface::PHINX_TYPE_TEXT, ['null' => true])
            ->save();
    }
}
