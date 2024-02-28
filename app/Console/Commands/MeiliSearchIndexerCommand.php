<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\MeiliSearch\SearchIndexer;
use Framework\Console\Command;
use Meilisearch\Exceptions\ApiException;

class MeiliSearchIndexerCommand extends Command
{
    public function __construct(
        protected readonly SearchIndexer $indexer
    ) {
        parent::__construct();
    }

    public static function signature(): string
    {
        return 'meili';
    }

    public function description(): string
    {
        return 'meilisearch keresomotor indexelese, konfiguralasa';
    }

    /**
     * @throws ApiException
     */
    public function handle()
    {
        if(!config('meilisearch.enabled')) {
            $this->output->warning('meilisearch is disabled, skipping...');
            return Command::SUCCESS;
        }

        $options = $this->getOptions();

        if (isset($options['create'])) {
            $this->indexer->createIndex();
        } elseif (isset($options['configure'])) {
            $this->indexer->configure();
        } elseif (isset($options['import'])) {
            $this->indexer->indexChurchGroups();
        } else {
            $this->output->error("invalid meili command.");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
