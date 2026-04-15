<?php

declare(strict_types=1);

use Baueri\Mint\View as MintView;

echo app(MintView::class)->render('partials/simple-pager.php', [
    'total' => $total,
    'page' => $page,
    'perpage' => $perpage,
]);
