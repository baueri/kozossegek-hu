<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

class Pager extends Module
{
    public function render(Context $context): string
    {
        $page    = (int) ($context->resolve('page') ?? 1);
        $total   = (int) ($context->resolve('total') ?? 0);
        $perpage = (int) ($context->resolve('perpage') ?? 18);
        $lastPage = max(1, (int) ceil($total / $perpage));

        if ($lastPage <= 1) {
            return '';
        }

        return $this->view($context, 'partials/pager.php', compact('page', 'total', 'perpage', 'lastPage'));
    }
}
