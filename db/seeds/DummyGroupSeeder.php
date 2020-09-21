<?php


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
        ];
        
        
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'name' => $randomNames[array_rand($randomNames)],
                'city' => 'Szeged',
                'description' => $faker->paragraphs(3, true),
                'denomination' => App\Enums\DenominationEnum::KATOLIKUS,
                'group_leaders' => $faker->name . (rand(0, 20) > 15 ? ', ' . $faker->name : ''),
                'group_leader_email' => $faker->email,
                'group_leader_phone' => $faker->phoneNumber,
                'spiritual_movement' => '',
                'age_group' => \App\Enums\AgeGroupEnum::random(),
                'occasion_frequency' => App\Enums\OccasionFrequencyEnum::random(),
                'status' => \App\Enums\GroupStatusEnum::ACTIVE,
                'institute_id' => $this->fetchRow('select id from institutes order by rand()')['id']
            ];
            
            $this->insert('groups', $data);
        }
    }
}
