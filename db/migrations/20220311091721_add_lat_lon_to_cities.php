<?php
declare(strict_types=1);

use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapQuery;
use Framework\Console\Out;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddLatLonToCities extends AbstractMigration
{
    public function up(): void
    {
        $this->table('cities')
            ->addColumn('lat', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->addColumn('lon', AdapterInterface::PHINX_TYPE_STRING, ['length' => 45])
            ->save();

        $osm = resolve(OpenStreetMapQuery::class);
        $cities = db()->select('select id, name from cities where lat = "" and name not like ? and name not like ?', ['%tanya%', '%dűlő%']);
        $cnt = count($cities);
        Out::info("cities to update: {$cnt}");
        foreach ($cities as $city) {
            $addr = $osm->search("Magyarország,{$city['name']}")[0] ?? [];
            if (!$addr) {
                continue;
            }

            db()->update('update cities set lat = ?, lon = ? where id = ?', $addr['lat'], $addr['lon'], $city['id']);
            if (--$cnt % 100 === 0) {
                Out::info("cities to update: {$cnt}");
            }
        }
    }
}
