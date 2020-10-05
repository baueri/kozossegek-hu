<?php

namespace App\Admin\Controllers\Api;

/**
 * Description of ApiPageController
 *
 * @author ivan
 */
class ApiPageController
{

    public function generateSlug(\Framework\Http\Request $request, \App\Repositories\PageRepository $repository)
    {
        $title = $request['title'];

        $slug = $originalSlug = \Framework\Support\StringHelper::slugify($title);

        while ($repository->findBySlug($slug)) {
            preg_match('/-([0-9]+)$/', $slug, $matches);

            $number = isset($matches[1]) ? $matches[1]+1 : 1;

            $slug = "$originalSlug-$number";
        }

        return ['success' => true, 'slug' => $slug];
    }
}
