<?php

namespace App\Admin\Controllers;

use App\Admin\SpiritualMovement\SpiritualMovementTable;
use App\Models\SpiritualMovement;
use App\Repositories\SpiritualMovements;
use Framework\Http\Request;
use Framework\Http\Message;
use Framework\Support\StringHelper;

class SpiritualMovementController
{
    public function spiritualMovements(SpiritualMovementTable $table)
    {
        $name = request()->get('name');
        return view('admin.spiritual_movement.spiritual_movements', compact('table', 'name'));
    }

    public function edit(SpiritualMovements $query)
    {
        $spiritualMovement = $query->findOrFail(request()->getUriValue('id'));
        $action = route('admin.spiritual_movement.update', $spiritualMovement);
        return view('admin.spiritual_movement.edit', compact('spiritualMovement', 'action'));
    }

    public function update(Request $request, SpiritualMovements $repo)
    {
        try {
            $data = $request->only(
                'name',
                'description',
                'website',
                'image_url',
                'highlighted'
            );

            $repo->query()->where('id', $request['id'])->update($data);
            Message::success('Sikeres mentés');
        } catch (\Exception $e) {
            process_error($e);
            Message::danger('Hiba történt a mentés során');
        } finally {
            redirect_route('admin.spiritual_movement.edit', ['id' => $request['id']]);
        }
    }

    public function create(Request $request)
    {
        return view('admin.spiritual_movement.create', [
            'spiritualMovement' => SpiritualMovement::make(),
            'action' => route('admin.spiritual_movement.do_create')
        ]);
    }

    public function doCreate(Request $request, SpiritualMovements $repo)
    {
        $data = $request->only('name', 'description', 'website', 'image_url');
        if ($repo->query()->where('name', $request['name'])->exists()) {
            Message::danger('Ez a lelkiségi mozgalom már létezik!');

            return view('admin.spiritual_movement.create', [
                'spiritualMovement' => SpiritualMovement::make($data),
                'action' => route('admin.spiritual_movement.do_create')
            ]);
        }
        $data['slug'] = StringHelper::slugify($request['name']);

        $movement = $repo->create($data);

        Message::success('Sikeres létrehozás');

        redirect_route('admin.spiritual_movement.edit', $movement);
    }


    public function delete(Request $request)
    {
        builder('spiritual_movements')->where('id', $request['id'])->delete();

        Message::danger('Lelkiségi mozgalom törölve');

        redirect_route('admin.spiritual_movement.list');
    }
}
