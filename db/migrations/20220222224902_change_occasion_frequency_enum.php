<?php
declare(strict_types=1);

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class ChangeOccasionFrequencyEnum extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('church_groups');

        $table->changeColumn('occasion_frequency', AdapterInterface::PHINX_TYPE_STRING)->save();

        $this->execute("update church_groups set occasion_frequency='hetente_tobbszor' where occasion_frequency='hetente-tobbszor'");

        $table->changeColumn(
            'occasion_frequency',
            AdapterInterface::PHINX_TYPE_ENUM,
            [
                'values' => [
                    'hetente_tobbszor',
                    'hetente',
                    'kethetente',
                    'havonta',
                    'egyeb'
                ],
                'comment' => 'milyen gyakran találkoznak a közösségek'
            ])->save();
    }

}
