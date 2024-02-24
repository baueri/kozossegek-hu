<?php

namespace App\Services\MeiliSearch;

use App\Enums\Lang;
use App\Models\ChurchGroupView;
use App\QueryBuilders\ChurchGroupViews;
use Meilisearch\Endpoints\Indexes;
use Meilisearch\Exceptions\ApiException;

class SearchIndexer
{
    protected readonly MeiliSearchAdapter $adapter;

    protected readonly Indexes $index;

    public function __construct(
        protected Lang $lang = Lang::hu
    ) {
        $this->adapter = new MeiliSearchAdapter($lang);
        $this->index = $this->adapter->index;
    }

    public function indexChurchGroups(): void
    {
        $churchGroups = ChurchGroupViews::query()
            ->active()
            ->with('tags')
            ->get();

        $this->index->deleteAllDocuments();

        $this->index->updateDocuments(
            $churchGroups->map('toMeiliSearch')
            ->all()
        );
    }

    /**
     * @throws ApiException
     */
    public function createIndex(): void
    {
        $this->index->create(MeiliSearchAdapter::INDEX_HU, ['primaryKey' => 'id']);
        $this->configure();
    }

    public function configure(): void
    {
        $this->index->updateSettings(config('meilisearch.settings'));
    }

    public function updateChurchGroupIndex(ChurchGroupView $group)
    {

    }
}
