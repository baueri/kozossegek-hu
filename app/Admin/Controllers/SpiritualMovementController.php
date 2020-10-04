<?php

namespace App\Admin\Controllers;

use Framework\Http\Request;
use Framework\Http\Message;

use Framework\Support\StringHelper;

class SpiritualMovementController
{
    public function spiritualMovements()
    {
        $movements = builder()
            ->select('*')
            ->from('spiritual_movements')
            ->orderBy('name', 'asc')
            ->get();
        return view('admin.group.spiritual_movements', compact('movements'));
    }

    public function update(Request $request)
    {

        $ok = builder('spiritual_movements')
            ->where('id', $request['id'])
            ->update(['name' => $request['value']]);

        return ['success' => $ok];
    }

    public function create(Request $request)
    {
        if (builder('spiritual_movements')->where('name', $request['name'])->exists()) {
            return ['success' => false, 'msg' => 'Ez a lelkiségi mozgalom már létezik.'];
        }

        builder('spiritual_movements')->insert([
            'name' => $request['name'],
            'slug' => StringHelper::slugify($request['name'])
        ]);
        
        Message::success('Sikeres létrehozás');

        return ['success' => true];
    }

    public function delete(Request $request)
    {
        builder('spiritual_movements')->where('id', $request['id'])->delete();

        return ['success' => true];
    }
}
