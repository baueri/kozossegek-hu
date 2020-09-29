<?php

namespace App\Repositories;
use App\Enums\DenominationEnum;
use App\Models\Denomination;
use ReflectionException;

/**
 * Description of DenominationRepository
 *
 * @author ivan
 */
class DenominationRepository {

    /**
     *
     * @return Denomination[]
     * @throws ReflectionException
     */
    public function all() : array
    {
        return DenominationEnum::values()->make(Denomination::class)->all();
    }
}
