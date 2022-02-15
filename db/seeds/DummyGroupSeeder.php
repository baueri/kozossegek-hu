<?php


use App\Enums\AgeGroupEnum;
use App\Enums\GroupStatusEnum;
use Phinx\Seed\AbstractSeed;


class DummyGroupSeeder extends AbstractSeed
{

    public function run()
    {
        $faker = Faker\Factory::create('hu_HU');
        $randomNames = [
            'Jezsu közi',
            'Katekumen csoport',
            'Puszta közi',
            'Segítő kör',
            'Gordiusz kör',
            'Ifi csoport',
            'Szegénykonyhások',
            'Talál-közi',
            'Lelkes',
            'KÉK énekkar',
            'Rókusi ifjúsági csoport',
            'SZICS (Szent-Mihályi ifjúsági csoport)',
            'Hit és fény',
            'Tiszavirág közösség',
            'Új Jeruzsálem közösség',
            'Katolikus Ifjúsági Mozgalom (KIM)',
            'Magvető',
            'Keresztény Ökomenikus Diákegylet',
            'Hálóklub',
            'Jegyesek',
            'Baba-mama klub',
            'Taizéi imakör',
            'Karitász',
            'CSÜCSOP',
            'Cursillo',
            'Házas Hétvége',
            'MÉCS',
            'Nyolc Boldogság katolikus közösség',
            'Hetvenkét Tanítvány Mozgalom',
            'Hét Láng közösség',
            'Antióchia közösség',
            'Actio Catholica',
            'Chemin Neuf közösség',
            'Bokor bázisközösség',
            'Bárka közösség',
            'Emmausz közösség',
            'Ferences világi rend',
            'Fokoláre mozgalom',
            'Mária kongregáció',
            'Mária légió',
            'Szeretetláng mozgalom',
            'Szentjánosbogár közösség',
            'Schönstatti apostoli mozgalom',
            'Regnum Christi Mozgalom',
        ];

        $movements = collect(db()->select('select id from spiritual_movements'))->pluck('id');

        for ($i = 0; $i < 1000; $i++) {
            $institute = $this->fetchRow('select id from institutes order by rand()');
            $data = [
                'name' => $randomNames[array_rand($randomNames)],
                'description' => $faker->paragraphs(3, true),
                'denomination' => App\Enums\DenominationEnum::KATOLIKUS,
                'group_leaders' => $faker->lastName . ' ' . $faker->firstName . (rand(0, 20) > 15 ? ', ' . $faker->lastName . ' ' . $faker->firstName : ''),
                'spiritual_movement_id' => $movements->random(),
                'age_group' => AgeGroupEnum::values()->random(),
                'occasion_frequency' => App\Enums\OccasionFrequencyEnum::values()->random(),
                'status' => GroupStatusEnum::ACTIVE,
                'institute_id' => $institute['id']
            ];

            $this->insert('groups', $data);
        }
    }
}
