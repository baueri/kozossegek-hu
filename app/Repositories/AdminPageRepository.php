<?php

namespace App\Repositories;

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

        $builder->orderByFromRequest();

        return $this->getInstances($builder->paginate());
    }
}
