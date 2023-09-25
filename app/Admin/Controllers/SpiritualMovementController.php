<?php

namespace App\Admin\Controllers;

use App\Admin\SpiritualMovement\SpiritualMovementTable;
use App\Models\SpiritualMovement;
use App\QueryBuilders\SpiritualMovements;
use Exception;
use Framework\Http\Controller;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Model\Exceptions\ModelNotFoundException;
use Framework\Support\StringHelper;

class SpiritualMovementController extends Controller
{
    public function spiritualMovements(Request $request, SpiritualMovementTable $table): string
    {
        $name = $request->get('name');
        $type = $table->type;
        return view(
            'admin.spiritual_movement.spiritual_movements',
            compact('table', 'name', 'type')
        );
    }

    /**
     * @throws ModelNotFoundException
     */
    public function edit(SpiritualMovements $query): string
    {
        $spiritualMovement = $query->findOrFail(request()->getUriValue('id'));
        $action = route('admin.spiritual_movement.update', $spiritualMovement);
        return view('admin.spiritual_movement.edit', compact('spiritualMovement', 'action'));
    }

    public function update(Request $request, SpiritualMovements $repo): void
    {
        try {
            $data = $request->only(
                'name',
                'description',
                'website',
                'image_url',
                'highlighted',
                'type'
            );

            $repo->where('id', $request['id'])->update($data);
            Message::success('Sikeres mentés');
        } catch (Exception $e) {
            process_error($e);
            Message::danger('Hiba történt a mentés során');
        } finally {
            redirect_route('admin.spiritual_movement.edit', ['id' => $request['id']]);
        }
    }

    public function create(Request $request): string
    {
        return view('admin.spiritual_movement.create', [
            'spiritualMovement' => SpiritualMovement::make(),
            'action' => route('admin.spiritual_movement.do_create')
        ]);
    }

    public function doCreate(Request $request, SpiritualMovements $repo): ?string
    {
        $data = $request->only('name', 'description', 'website', 'image_url', 'type', 'highlighted');
        if ($repo->where('name', $request['name'])->exists()) {
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


    public function delete(Request $request): void
    {
        builder('spiritual_movements')->where('id', $request['id'])->delete();

        Message::danger('Lelkiségi mozgalom törölve');

        redirect($this->request->referer());
    }
}
