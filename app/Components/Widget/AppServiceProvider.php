<?php

namespace App\Components\Widget;

use Framework\Http\View\View;
use Framework\Middleware\Middleware;
use Framework\Http\View\ViewParser;
use App\Repositories\Widgets;

class AppServiceProvider implements Middleware
{

    private $repo;

    public function __construct(Widgets $repo)
    {
        $this->repo = $repo;
    }

    public function handle()
    {
        ViewParser::registerDirective('widget', function ($matches) {
            return "<?php echo widget({$matches[1]})->render(); ?>";
        });

        View::setVariable('is_home', is_home());
        View::setVariable('is_prod', is_prod());
        View::setVariable('header_background', '');
    }
}
