<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ReplaceHyphenToUnderscoreInGroupTagsTable extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(<<<SQL
            UPDATE group_tags SET tag = REPLACE(tag, "-", "_");
        SQL);

        $this->execute(<<<SQL
            UPDATE group_tags SET tag = "szintarsulat" WHERE tag = "szntrsulat"
        SQL);
    }

    public function down()
    {
        $this->execute(<<<SQL
            UPDATE group_tags SET tag = REPLACE(tag, "_", "-");
        SQL);


        $this->execute(<<<SQL
            UPDATE group_tags SET tag = "szntrsulat" WHERE tag = "szintarsulat"
        SQL);
    }
}
