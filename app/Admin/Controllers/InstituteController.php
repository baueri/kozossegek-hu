<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;

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

        if ($image = $request['image']) {
            if (!file_exists(ROOT . 'public/media/institutes/')) {
                mkdir(ROOT . 'public/media/institutes/');
            }
            file_put_contents(ROOT . 'public/media/institutes/inst_' . $institute->id . '.jpg', base64_decode(substr($image, strpos($image,','))));
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
    
    public function doImport(Request $request, Institutes $repository, \App\Auth\Auth $auth)
    {
        try {
            $file = $request->files['import_file'];

            $rows = array_map('str_getcsv', file($file['tmp_name']));

            unset($rows[0]);
            $skipped = $imported = 0;
            foreach ($rows as $row) {
                $instituteData = [
                    'name' => $row[0],
                    'city' => mb_ucfirst(mb_strtolower($row[1])),
                    'district' => mb_ucfirst(mb_strtolower($row[2])),
                    'address' => $row[3],
                    'leader_name' => $row[4],
                    'user_id' => $auth->user()->id
                ];
                if (!builder('institutes')->where('name', $instituteData['name'])->where('city', $instituteData['city'])->exists()) {
                    $imported++;
                    $repository->create($instituteData);
                } else {
                    $skipped++;
                }
            }

            Message::success("Sikeres importálás. <b>$imported</b> intézmény importálva, <b>$skipped</b> kihagyva duplikáció miatt");
        } catch(\Exception $e) {
            Message::danger('Sikertelen importálás');
        }
        
        return redirect_route('admin.institute.import');
        
    }
}
