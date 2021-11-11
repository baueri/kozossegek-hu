<?php

namespace App\Repositories;
use App\Enums\DenominationEnum;
use App\Models\Denomination;
use ReflectionException;

/**
 * Description of Denominations
 *
 * @author ivan
 */
class Denominations {

    /**
     *
     * @return Denomination[]
     * @throws ReflectionException
     */
    public function all() : array
    {
        return DenominationEnum::values()->as(Denomination::class)->all();
    }
}
