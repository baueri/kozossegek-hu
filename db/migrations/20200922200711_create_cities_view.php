<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCitiesView extends AbstractMigration
{

    public function up(): void
    {
        $this->execute('CREATE OR REPLACE VIEW v_cities as (
            SELECT cities.*, counties.name as county FROM cities LEFT JOIN counties
            ON county_id=counties.id
            )');
    }
}
