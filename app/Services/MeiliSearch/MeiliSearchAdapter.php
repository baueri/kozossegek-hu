<?php

namespace App\Services\MeiliSearch;

use App\Enums\Lang;
use Meilisearch\Client;
use Meilisearch\Endpoints\Indexes;

class MeiliSearchAdapter
{
    public final const INDEX_HU = 'church_groups_hu';
    public final const INDEX_EN = 'church_groups_en';

    public readonly Client $client;

    public readonly Indexes $index;
    public function __construct(Lang $lang = Lang::hu)
    {
        $this->client = new Client(env('MEILI_HOST'), env('MEILI_MASTER_KEY'));
        $this->index = $this->client->index(
            match ($lang) {
                Lang::hu => self::INDEX_HU,
                Lang::en => self::INDEX_EN
            }
        );
    }
}
