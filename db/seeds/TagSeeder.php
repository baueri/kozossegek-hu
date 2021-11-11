<?php

use Phinx\Seed\AbstractSeed;
use Framework\Support\StringHelper;

class TagSeeder extends AbstractSeed
{
    public function run()
    {
        $tags = collect_file(ROOT . 'db/sources/tags.txt');

        foreach ($tags as $tag) {
            $slug = StringHelper::slugify($tag);
            $this->execute("replace into tags (tag, slug) values('$tag', '$slug')");
        }
    }
}
