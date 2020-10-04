<?php


use Phinx\Seed\AbstractSeed;

use Framework\Support\StringHelper;

class SpiritualMovementSeeder extends AbstractSeed
{

    public function run()
    {
        $rows = array_filter(explode(PHP_EOL, file_get_contents(ROOT . 'db' . DS . 'sources' . DS . 'spiritual_movements.txt')));

        foreach ($rows as $row) {
            $slug = StringHelper::slugify($row);
            $this->execute("replace into spiritual_movements (name, slug) values('$row', '$slug')");
        }
    }
}
