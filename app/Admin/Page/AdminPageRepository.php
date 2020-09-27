<?php

namespace App\Admin\Page;

/**
 * Description of AdminPageRepository
 *
 * @author ivan
 */
class AdminPageRepository extends \App\Repositories\PageRepository {
    public function getPages($filter = array()) {
        
        $builder = $this->getBuilder();
        
        if ($status = $filter['status']) {
            $builder->where('status', $status);
        }
        
        if ($deleted = $filter['deleted']) {
            
            $builder->where('deleted_at IS NOT NULL');
        } else {
            $builder->where('deleted_at IS NULL');
        }
        
        return $this->getInstances($builder->paginate(30));
    }
}
