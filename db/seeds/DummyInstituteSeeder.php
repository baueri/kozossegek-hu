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
         
         for ($i = 0; $i < 20; $i++) {
             $data = [
                 'name' => $faker->company . ' ' . $suffixes[array_rand($suffixes)],
                 'city' => 'Szeged',
                 'address' => $faker->address,
                 'leader_name' => $faker->name('male') . ' atya'
             ];
             
             $this->insert('institutes', $data);
         }
    }
}
