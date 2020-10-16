<?php

namespace App\Components\Widget;

use Framework\Middleware\Middleware;
use Framework\Http\View\ViewParser;
use App\Repositories\Widgets;

class WidgetServiceProvider implements Middleware
{

    private $repo;

    public function __construct(Widgets $repo)
    {
        $this->repo = $repo;
    }

    public function handle()
    {
        
        ViewParser::registerDirective('widget', function($matches) {
            return "<?php echo widget({$matches[1]})->render(); ?>";
        });
    }
}
