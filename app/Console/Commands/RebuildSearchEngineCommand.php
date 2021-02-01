<?php

namespace App\Console\Commands;

use App\Services\RebuildSearchEngine;
use Framework\Console\Command;
use Framework\Console\Out;

/**
 * Description of RebuildSearchEngineCommand
 *
 * @author ivan
 */
class RebuildSearchEngineCommand implements Command {

    /**
     * @var RebuildSearchEngine
     */
    private $service;

    public function __construct(RebuildSearchEngine $service) {
        $this->service = $service;
    }

    public function handle() {
        Out::writeln('rebuilding search engine rows...');
        $this->service->run();
        Out::success('done.');
    }

    public static function signature() {
        return 'search-engine:rebuild';
    }

}
