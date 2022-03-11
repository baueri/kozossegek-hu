<?php
declare(strict_types=1);

use App\QueryBuilders\Institutes;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapQuery;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddLatLonToInstitutesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('institutes')
            ->addColumn('lat', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->addColumn('lon', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->save();

        $osm = resolve(OpenStreetMapQuery::class);
        $institutes = Institutes::query()
            ->where('lat', '')
            ->notDeleted()
            ->get();
        foreach ($institutes as $institute) {
            $latlon = collect([$institute->address, $institute->name, $institute->name2])->filter()->firstNonEmpty(function ($address) use ($osm, $institute) {
                return $osm->getLatLon("{$institute->city},{$address}");
            });
            if ($latlon) {
                Institutes::query()->save($institute, $latlon);
            }
        }

    }
}
