<?php

namespace App\Admin\Controllers;

use App\Repositories\Institutes;
use App\Models\Institute;
use Framework\Http\Request;
use Framework\Http\Message;
use App\Auth\Auth;
use App\Admin\Institute\InstituteAdminTable;
use App\Storage\Base64Image;

/**
 * Description of InstituteController
 *
 * @author ivan
 */
class InstituteController extends AdminController
{
    /**
     * @param Request $request
     * @param InstituteAdminTable $table
     * @return string
     */
    public function list(Request $request, InstituteAdminTable $table)
    {
        $city = $request['city'];
        $search = $request['search'];
        return view('admin.institute.list', compact('table', 'city', 'search'));
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

        if ($imageSource = $request['image']) {
            $image = new Base64Image($imageSource);
            $image->saveImage($institute->getImageStoragePath());
        }
//dd($request->all());
        $institute->update($request->only('name', 'city', 'district', 'address', 'leader_name', 'approved'));

        $repository->save($institute);

        Message::success('Sikeres mentés');

        redirect_route('admin.institute.edit', $institute);
    }

    public function create()
    {
        $action = route('admin.institute.do_create');
        $institute = new Institute();

        return view('admin.institute.create', compact('action', 'institute'));
    }

    public function doCreate(Request $request, Institutes $repository)
    {
        $data = $request->only('name', 'city', 'district', 'address', 'leader_name');
        $data['user_id'] = Auth::user()->id;
        $data['approved'] = 1;
        $institute = $repository->create($data);

        if ($image = $request['image']) {
            if (!file_exists(ROOT . 'public/media/institutes/')) {
                mkdir(ROOT . 'public/media/institutes/');
            }
            file_put_contents(ROOT . 'public/media/institutes/inst_' . $institute->id . '.jpg', base64_decode(substr($image, strpos($image, ','))));
        }

        Message::success('Új intézmény létrehozva');

        redirect_route('admin.institute.edit', $institute);
    }

    public function delete(Request $request, Institutes $repository)
    {
        $repository->delete($repository->findOrFail($request['id']));

        Message::warning('Intézmény törölve');

        redirect_route('admin.institute.list');
    }

    public function import()
    {
        return view('admin.institute.import');
    }

    public function doImport(Request $request, \App\Auth\Auth $auth, \App\Services\InstituteImporter $service)
    {
        try {
            $file = $request->files['import_file'];

            [$imported, $skipped] = $service->run($file['tmp_name'], $auth->user());

            Message::success("Sikeres importálás. <b>$imported</b> intézmény importálva, <b>$skipped</b> kihagyva duplikáció miatt");
        } catch (\Exception $e) {
            Message::danger('Sikertelen importálás');
        }

        return redirect_route('admin.institute.import');
    }
}
