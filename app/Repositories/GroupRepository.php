<?php

namespace App\Repositories;

class GroupRepository extends \Framework\Repository
{
    
    public function all($page = 1, $perPage = 30)
    {
        $result = $this->getBuilder()->paginate($page ?: 1, $perPage);
        
        return [
            'total' => $result['total'],
            'rows' => $this->getInstances($result['rows'])
        ];
    }
    
    public function search($keyword, \Framework\Support\Collection $filter, $page = 1, $perPage = 30)
    {
        $builder = $this->getBuilder();
        
        if ($keyword) {
            $builder->where('name', 'like', "%$keyword%");
        }
        
        if ($varos = $filter['varos']) {
            $builder->where('city', $varos);
        }
        
        
        $result = $builder->paginate($page ?: 1, $perPage);
        
        return [
            'total' => $result['total'],
            'rows' => $this->getInstances($result['rows']),
            'perpage' => $perPage
        ];
    }
    
    /**
     * @param string $slug
     * @return \App\Models\Group
     */
    public function findBySlug($slug)
    {
        $id = substr($slug, strrchr($slug, '-'));
        
        return $this->find($id);
    }

    public static function getModelClass(): string {
        return \App\Models\Group::class;
    }

    public static function getTable(): string {
        return 'groups';
    }

}
