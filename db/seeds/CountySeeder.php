<?php


use Phinx\Seed\AbstractSeed;

class CountySeeder extends AbstractSeed
{

    public function run()
    {
        $content = file_get_contents(__DIR__ . '/../sources/counties.json');
        $rows = json_decode($content, true);
        
        $this->table('counties')->truncate();
        
        foreach ($rows as $row) {
            $this->table('counties')->insert($row)->save();
        }
        
    }
}
