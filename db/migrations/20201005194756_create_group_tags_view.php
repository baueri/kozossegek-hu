<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateGroupTagsView extends AbstractMigration
{
    public function up(): void
    {
        $this->execute('CREATE OR REPLACE VIEW v_group_tags AS
            SELECT
                group_tags.*, tags.tag as tag_name
            FROM group_tags
            LEFT JOIN tags ON group_tags.tag=tags.slug');
    }
}
