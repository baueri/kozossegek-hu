<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;

use App\Repositories\Institutes;
use App\Models\Institute;
use Framework\Http\Request;
use Framework\Http\Message;
use App\Auth\Auth;
use App\Admin\Institute\InstituteAdminTable;

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

        if ($image = $request['image']) {
            if (!file_exists(ROOT . 'public/media/institutes/')) {
                mkdir(ROOT . 'public/media/institutes/');
            }
            $imageSource = base64_decode(substr($image, strpos($image,',')));
            $thumbnail = imagecrop(imagecreatefromstring($imageSource), ['x' => 0, 'y' => 350, 'width' => 400, 'height' => 250]);
            ob_start();
            imagejpeg($thumbnail);
            $thumnailSource = ob_get_clean();

            //
            // $stamp = imagecreatefrompng(ROOT . 'resources/watermark.png');
            // $img = imagecreatefromstring($imageSource);
            //
            // $marge_right = 10;
            // $marge_bottom = 10;
            // $sx = imagesx($stamp);
            // $sy = imagesy($stamp);
            //
            // imagecopy($img, $stamp, imagesx($img) - $sx - $marge_right, imagesy($img) - $sy - $marge_bottom, 0, 0, $sx, $sy);
            // ob_start();
            // imagejpeg($img);
            // $contents = ob_get_clean();

            file_put_contents(ROOT . 'public/media/institutes/inst_' . $institute->id . '.jpg', $imageSource);
            file_put_contents(ROOT . 'public/media/institutes/inst_' . $institute->id . '_wide.jpg', $thumnailSource);

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
}
