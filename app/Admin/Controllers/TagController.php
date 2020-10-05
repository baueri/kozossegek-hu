<?php

namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use Framework\Http\Request;
use Framework\Support\StringHelper;
use Framework\Http\Message;

class TagController extends AdminController
{
    public function tags()
    {
        $tags = builder('tags')->select('*')->get();

        return view('admin.tag.tags', compact('tags'));
    }

        public function update(Request $request)
        {

            $ok = builder('tags')
                ->where('id', $request['id'])
                ->update(['tag' => $request['tag']]);

            return ['success' => $ok];
        }

        public function create(Request $request)
        {
            if (builder('tags')->where('tag', $request['tag'])->exists()) {
                return ['success' => false, 'msg' => 'Ez a címke már létezik.'];
            }

            builder('tags')->insert([
                'tag' => $request['tag'],
                'slug' => StringHelper::slugify($request['tag'])
            ]);

            Message::success('Sikeres létrehozás');

            return ['success' => true];
        }

        public function delete(Request $request)
        {
            builder('tags')->where('id', $request['id'])->delete();

            return ['success' => true];
        }
}
