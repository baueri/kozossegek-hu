<?php

namespace App\Admin\Controllers\Api;

use App\Repositories\PageRepository;
use Framework\Http\Request;
use Framework\Support\StringHelper;

class ApiPageController
{
    public function generateSlug(Request $request, PageRepository $repository): array
    {
        $title = $request['title'];

        $slug = $originalSlug = StringHelper::slugify($title);

        while ($repository->findBySlug($slug)) {
            preg_match('/-([0-9]+)$/', $slug, $matches);

            $number = isset($matches[1]) ? $matches[1] + 1 : 1;

            $slug = "$originalSlug-$number";
        }

        return ['success' => true, 'slug' => $slug];
    }
}
