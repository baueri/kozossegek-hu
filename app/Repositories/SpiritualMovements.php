<?php

namespace App\Repositories;

class SpiritualMovements
{
    public function all()
    {
        return db()->select('select * from spiritual_movements');
    }

    public function find($id)
    {
        return builder('spiritual_movements')->where('id', $id)->first();
    }
}
