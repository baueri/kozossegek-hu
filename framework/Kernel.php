<?php

declare(strict_types=1);

namespace Framework;

interface Kernel
{
    public function handleError($error);
}
