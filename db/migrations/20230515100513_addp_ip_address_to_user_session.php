<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddpIpAddressToUserSession extends AbstractMigration
{
    public function change(): void
    {
        $this->table('user_sessions')
            ->addColumn('user_agent', AdapterInterface::PHINX_TYPE_STRING)
            ->addColumn('ip_address', AdapterInterface::PHINX_TYPE_STRING)
            ->save();
    }
}
