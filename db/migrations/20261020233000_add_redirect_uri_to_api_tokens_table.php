<?php

declare(strict_types=1);

use App\Migration\AppMigration;
use Phinx\Db\Adapter\AdapterInterface;

final class AddRedirectUriToApiTokensTable extends AppMigration
{
    public function up(): void
    {
        $this->table('api_tokens')
            ->addColumn('redirect_uri', AdapterInterface::PHINX_TYPE_STRING, [
                'length' => 500,
                'null' => true,
                'after' => 'grant_type',
            ])
            ->update();
    }

    public function down(): void
    {
        $table = $this->table('api_tokens');
        if ($table->hasColumn('redirect_uri')) {
            $table->removeColumn('redirect_uri')->update();
        }
    }
}
