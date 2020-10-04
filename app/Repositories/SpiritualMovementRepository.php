<?php

namespace App\Repositiories;

class SpiritualMovementRepository
{
    public function all()
    {
        return db()->get('select * from spiritual_movements');
    }
}
