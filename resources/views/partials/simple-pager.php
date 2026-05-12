<?php

use Baueri\Mint\View as MintView;

echo app()->get(MintView::class)->render('partials/simple-pager.php', [
    'total' => $total,
    'page' => $page,
    'perpage' => $perpage,
]);
