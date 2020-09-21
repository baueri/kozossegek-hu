<?php

namespace App\Repositories;

class GroupRepository extends \Framework\Repository
{
    const PERPAGE = 30;
    
    
    public function all($page = 1)
    {
        $result = $this->getBuilder()->paginate($page ?: 1, self::PERPAGE);
        
        return [
            'total' => $result['total'],
            'rows' => $this->getInstances($result['rows'])
        ];
    }
    
    public function search($keyword, $filter = [], $page = 1)
    {
        $builder = $this->getBuilder();
        
        $words = explode(' ', $keyword);
        
        
        foreach ($words as $word) {
            $builder->where('name', 'like', "%$word%", 'or');
        }
        
        $result = $builder->paginate($page ?: 1, self::PERPAGE);
        
        return [
            'total' => $result['total'],
            'rows' => $this->getInstances($result['rows']),
            'perpage' => self::PERPAGE
        ];
    }

    public static function getModelClass(): string {
        return \App\Models\Group::class;
    }

    public static function getTable(): string {
        return 'groups';
    }

}
