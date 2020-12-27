<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class FixBudapestDistrictNames extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up(): void
    {
        $this->execute('UPDATE `institutes` set district=CONCAT(UPPER(substring_index(district, ".", 1)), ". kerület") where district like "%. kerület%"');

        if ($this->table('institutes')->hasColumn('distrcict')) {
            $this->table('institutes')->removeColumn('distrcict')
                ->save();
        }
    }
}
