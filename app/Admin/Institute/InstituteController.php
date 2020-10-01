<?php

namespace App\Admin\Institute;

use App\Admin\Controllers\AdminController;

use App\Repositories\InstituteRepository;

use Framework\Http\Request;
use Framework\Http\Message;

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

    public function edit(Request $request, InstituteRepository $repository)
    {
        $institute = $repository->findOrFail($request['id']);
        $action = route('admin.institute.update', $institute);

        return view('admin.institute.edit', compact('institute', 'action'));
    }

    public function update(Request $request, InstituteRepository $repository)
    {
        $institute = $repository->findOrFail($request['id']);

        $institute->update($request->only('name', 'city', 'district', 'address', 'leader_name'));

        $repository->save($institute);

        Message::success('Sikeres mentés');

        redirect('admin.institute.edit', $institute);
    }

    public function create()
    {
        $action = route('admin.institute.do_create');

        return view('admin.institute.create', compact('action'));
    }

    public function doCreate(Request $request, InstituteRepository $repository)
    {
        $institute = $repository->create($request->only('name', 'city', 'district', 'address', 'leader_name'));

        Message::success('Új intézmény létrehozva');

        redirect('admin.institute.edit', $institute);
    }
}
