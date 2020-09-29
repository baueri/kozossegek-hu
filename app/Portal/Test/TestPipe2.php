<?php


namespace App\Portal\Test;


use App\Enums\OccasionFrequencyEnum;
use Framework\Database\Builder;
use Framework\Support\PipeLine\Pipe;

class TestPipe2 extends Pipe
{
    /**
     * @param Builder $builder
     */
    public function handle($builder)
    {
        $builder->where('occasion_frequency', OccasionFrequencyEnum::HETENTE);

        $this->next($builder);
    }
}