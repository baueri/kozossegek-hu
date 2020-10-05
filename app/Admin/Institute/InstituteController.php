<?php

namespace App\Admin\Institute;

use App\Admin\Controllers\AdminController;

use App\Repositories\Institutes;
use App\Models\Institute;
use Framework\Http\Request;
use Framework\Http\Message;
use App\Auth\Auth;

/**
 * Description of InstituteController
 *
 * @author ivan
 */
class InstituteController extends AdminController
{
    /**
     * @param InstituteAdminTable $table
     * @return string
     */
    public function list(InstituteAdminTable $table)
    {
        return view('admin.institute.list', compact('table'));
    }

    public function edit(Request $request, Institutes $repository)
    {
        $institute = $repository->find($request['id']);

        if (!$institute) {

            Message::danger('A keresett intézmény nem található');

            redirect_route('admin.institute.list');
        }

        $action = route('admin.institute.update', $institute);

        return view('admin.institute.edit', compact('institute', 'action'));
    }

    public function update(Request $request, Institutes $repository)
    {
        $institute = $repository->findOrFail($request['id']);

        $institute->update($request->only('name', 'city', 'district', 'address', 'leader_name'));

        $repository->save($institute);

        Message::success('Sikeres mentés');

        redirect_route('admin.institute.edit', $institute);
    }

    public function create()
    {
        $action = route('admin.institute.do_create');
        $institute = new Institute;

        return view('admin.institute.create', compact('action', 'institute'));
    }

    public function doCreate(Request $request, Institutes $repository)
    {
        $data = $request->only('name', 'city', 'district', 'address', 'leader_name');
        $data['user_id'] = Auth::user()->id;
        $institute = $repository->create($data);

        Message::success('Új intézmény létrehozva');

        redirect_route('admin.institute.edit', $institute);
    }

    public function delete(Request $request, Institutes $repository)
    {
        $repository->delete($repository->findOrFail($request['id']));

        Message::warning('Intézmény törölve');

        redirect_route('admin.institute.list');
    }
}
