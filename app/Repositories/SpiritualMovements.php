<?php

namespace App\Repositiories;

class SpiritualMovements
{
    public function all()
    {
        return db()->get('select * from spiritual_movements');
    }
    
    public function find($id)
    {
        return builder('spiritual_movements')->where('id', $id)->first();
    }
}
