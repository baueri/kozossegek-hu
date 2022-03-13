<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class ChangeCityCollactionInStatCityTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('stat_city')
            ->changeColumn('city', AdapterInterface::PHINX_TYPE_STRING, ['collation' => 'utf8_bin'])
            ->save();
    }
}
