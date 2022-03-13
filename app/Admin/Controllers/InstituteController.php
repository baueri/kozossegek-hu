<?php

namespace App\Admin\Controllers;

use App\Admin\Institute\InstituteAdminTable;
use App\Auth\Auth;
use App\Helpers\InstituteHelper;
use App\Services\InstituteImporter;
use App\Storage\Base64Image;
use Framework\Http\Message;
use Framework\Http\Request;
use Legacy\Institute;
use Legacy\Institutes;

class InstituteController extends AdminController
{
    /**
     * @param Request $request
     * @param InstituteAdminTable $table
     * @return string
     */
    public function list(Request $request, InstituteAdminTable $table): string
    {
        $city = $request['city'];
        $search = $request['search'];
        return view('admin.institute.list', compact('table', 'city', 'search'));
    }

    public function edit(Request $request, Institutes $repository): string
    {
        /* @var $institute \Legacy\Institute */
        $institute = $repository->find($request['id']);
        $images = collect(builder('miserend_photos')->where('church_id', $institute->miserend_id)->get())
            ->map(fn($row) => InstituteHelper::getMiserendImagePath($institute, $row['filename']));

        if (!$institute) {
            Message::danger('A keresett intézmény nem található');

            redirect_route('admin.institute.list');
        }

        $action = route('admin.institute.update', $institute);

        return view('admin.institute.edit', compact('institute', 'action', 'images'));
    }

    public function update(Request $request, Institutes $repository)
    {
        $institute = $repository->findOrFail($request['id']);

        if ($imageSource = $request['image']) {
            $image = new Base64Image($imageSource);
            $image->saveImage($institute->getImageStoragePath());
            $institute->image_url = InstituteHelper::getImageRelPath($institute->id);
        }

        $institute->update($request->only(
            'name',
            'name2',
            'city',
            'district',
            'address',
            'leader_name',
            'approved',
            'image_url',
            'website'
        ));

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
        $data['user_id'] = Auth::user()->id ?? null;
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

    public function doImport(Request $request, InstituteImporter $service)
    {
        try {
            $file = $request->files['import_file'];

            [$imported, $skipped] = $service->run($file['tmp_name'], Auth::user());

            Message::success("Sikeres importálás. <b>$imported</b> intézmény importálva, <b>$skipped</b> kihagyva duplikáció miatt");
        } catch (\Exception $e) {
            Message::danger('Sikertelen importálás');
        }

        return redirect_route('admin.institute.import');
    }
}
