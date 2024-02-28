<?php

declare(strict_types=1);

namespace App\Portal\BreadCrumb;

interface BreadCrumbable
{
    public function getBreadCrumb(): BreadCrumb;
}
