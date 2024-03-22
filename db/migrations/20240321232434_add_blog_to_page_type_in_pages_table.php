<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddBlogToPageTypeInPagesTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('pages')
            ->changeColumn('page_type', 'enum', ['values' => ['page', 'announcement', 'blog'], 'default' => 'page'])
            ->update();
    }

    public function down(): void
    {
        $this->table('pages')
            ->changeColumn('page_type', 'enum', ['values' => ['page', 'announcement'], 'default' => 'page'])
            ->update();
    }
}
