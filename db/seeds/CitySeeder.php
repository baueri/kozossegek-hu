<?php


use Phinx\Seed\AbstractSeed;

class CitySeeder extends AbstractSeed
{
    
    public function run()
    {
        $content = file_get_contents(__DIR__ . '/../sources/cities.json');
        $rows = json_decode($content, true);
        
        $this->table('cities')->truncate();
        
        foreach ($rows as $row) {
            $this->table('cities')->insert($row)->save();
        }
        
    }
}
