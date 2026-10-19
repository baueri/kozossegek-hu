<?php

declare(strict_types=1);

use App\Migration\AppMigration;

final class AddFeaturedImagePosterToEventsTable extends AppMigration
{
    public function change(): void
    {
        $this->table('events')
            ->addColumn('featured_image_poster', 'string', ['null' => true, 'after' => 'featured_image'])
            ->update();
    }
}
