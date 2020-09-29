<?php

namespace App\Portal\Test;

use Framework\Database\Builder;
use Framework\Support\PipeLine\Pipe;

class TestPipe1 extends Pipe
{

    /**
     * @param Builder $builder
     */
    public function handle($builder)
    {
        $builder->where('age_group', 'tinedzser');

        $this->next($builder);
    }
}