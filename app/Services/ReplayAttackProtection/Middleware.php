<?php

declare(strict_types=1);

namespace App\Services\ReplayAttackProtection;

use Framework\Http\Request;
use Framework\Middleware\After;
use Framework\Middleware\Before;

readonly class Middleware implements Before, After
{
    public function __construct(
        protected Request $request,
        protected Service $service
    ) {
    }

    /**
     * @throws Exception
     */
    public function before(): void
    {
        if (!$this->service->validate($this->request->get('rap'))) {
            throw new Exception('Replay attack detected');
        }
    }

    public function after(): void
    {
        $this->service->forget($this->request->get('rap'));
    }
}
