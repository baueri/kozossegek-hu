<?php
declare(strict_types=1);

use App\Models\Institute;
use App\QueryBuilders\Institutes;
use Framework\Support\StringHelper;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Migration\AbstractMigration;

final class AddSlugToChurchTable extends AbstractMigration
{
    public function up(): void
    {
        $this->table('institutes')
            ->addColumn('slug', AdapterInterface::PHINX_TYPE_STRING)
            ->save();

        $repo = Institutes::query();
        $repo->each(fn (Institute $institute) => $repo->save(
            $institute->fill(['slug' => StringHelper::slugify($institute->name)]))
        );
    }
}
