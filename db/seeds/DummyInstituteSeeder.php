<?php


use Phinx\Seed\AbstractSeed;

class DummyInstituteSeeder extends AbstractSeed
{

    public function run()
    {
        $faker = \Faker\Factory::create('hu_HU');
        
         $suffixes = [
             'templom',
             'plébánia',
             'lelkészség',
             'általános iskola',
             'gimnázium',
             'kórház',
             'rendház'
         ];
         
         for ($i = 0; $i < 100; $i++) {
             $data = [
                 'name' => $faker->company . ' ' . $suffixes[array_rand($suffixes)],
                 'city' => $faker->city,
                 'address' => $faker->address,
                 'leader_name' => $faker->lastName . ' ' . $faker->firstNameMale . ' atya'
             ];
             
             $this->insert('institutes', $data);
         }
    }
}
