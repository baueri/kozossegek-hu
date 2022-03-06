<?php

namespace App\Admin\Controllers\Api;

use App\QueryBuilders\Pages;
use Framework\Http\Request;
use Framework\Support\StringHelper;

class ApiPageController
{
    public function generateSlug(Request $request, Pages $repository): array
    {
        $title = $request['title'];

        $slug = $originalSlug = StringHelper::slugify($title);

        while ($repository->whereSlug($slug)->first()) {
            preg_match('/-([0-9]+)$/', $slug, $matches);

            $number = isset($matches[1]) ? $matches[1] + 1 : 1;

            $slug = "$originalSlug-$number";
        }

        return ['success' => true, 'slug' => $slug];
    }
}
