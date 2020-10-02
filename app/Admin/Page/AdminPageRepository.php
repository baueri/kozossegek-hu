<?php

namespace App\Admin\Page;

use App\Repositories\PageRepository;

/**
 * Description of AdminPageRepository
 *
 * @author ivan
 */
class AdminPageRepository extends PageRepository
{

    public function getPages($filter)
    {

        $builder = $this->getBuilder();

        if ($status = $filter['status']) {
            $builder->where('status', $status);
        }

        if ($filter['deleted']) {
            $builder->whereNotNull('deleted_at');
        } else {
            $builder->whereNull('deleted_at');
        }

        if ($search = $filter['search']) {
            $builder->where('title', 'like', "%$search%");
        }

        return $this->getInstances($builder->paginate(30));
    }
}
