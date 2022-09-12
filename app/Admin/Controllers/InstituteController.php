<?php

namespace App\Admin\Controllers;

use App\Admin\Institute\InstituteAdminTable;
use App\Auth\Auth;
use App\Helpers\InstituteHelper;
use App\Models\Institute;
use App\QueryBuilders\Institutes;
use App\Services\InstituteImporter;
use App\Storage\Base64Image;
use Exception;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;

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

    /**
     * @throws ModelNotFoundException
     */
    public function update(Request $request, Institutes $repository): void
    {
        $institute = $repository->findOrFail($request['id']);

        if ($imageSource = $request['image']) {
            $image = new Base64Image($imageSource);
            $image->saveImage($institute->getImageStoragePath());
            $institute->image_url = InstituteHelper::getImageRelPath($institute->id);
        }

        $repository->save(
            $institute,
            $request->only([
                'name',
                'name2',
                'city',
                'district',
                'address',
                'leader_name',
                'approved',
                'image_url',
                'website'
            ])
        );

        Message::success('Sikeres mentés');

        redirect_route('admin.institute.edit', $institute);
    }

    public function create(): string
    {
        $action = route('admin.institute.do_create');
        $institute = new Institute();

        return view('admin.institute.create', compact('action', 'institute'));
    }

    public function doCreate(Request $request, Institutes $repository): never
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

    public function delete(Request $request, Institutes $repository): never
    {
        $repository->where('id', $request['id'])->softDelete();

        Message::warning('Intézmény törölve');

        redirect_route('admin.institute.list');
    }

    public function import(): string
    {
        return view('admin.institute.import');
    }

    public function doImport(Request $request, InstituteImporter $service): never
    {
        try {
            $file = $request->files['import_file'];

            [$imported, $skipped] = $service->run($file['tmp_name'], Auth::user());

            Message::success("Sikeres importálás. <b>$imported</b> intézmény importálva, <b>$skipped</b> kihagyva duplikáció miatt");
        } catch (Exception $e) {
            report($e);
            Message::danger('Sikertelen importálás');
        }

        redirect_route('admin.institute.import');
    }
}
