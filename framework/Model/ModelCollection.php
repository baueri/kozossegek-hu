<?php


namespace Framework\Model;


use Framework\Support\Collection;

class ModelCollection extends Collection
{
    /**
     *
     * @param mixed $relations
     * @param $toLoad
     * @param $on
     * @param string $target
     * @return static
     */
    public function with($relations, $toLoad, $on, $target = 'id')
    {
        if (!$relations) {
            return $this;
        }
        
        foreach ($this->items as $item) {
            $found = static::getAttributeValues($relations, $target, $item->{$on});
            $item->{$toLoad} = $found[0] ?? null;
        }
        
        return $this;
    }

    public function withMany($relations, $toLoad, $on, $target = 'id')
    {
        if (!$relations) {
            return $this;
        }

        foreach ($this->items as $item) {
            $item->{$toLoad} = static::getAttributeValues($relations, $target, $item->{$on});
        }

        return $this;
    }
    
    /**
     * 
     * @param mixed $relations
     * @param string $target
     * @param mixed $onValue
     * @return mixed
     */
    private static function getAttributeValues($relations, $target, $onValue)
    {
        $found = [];
        foreach ($relations as $relation) {
            if (is_array($relation)) {
                if (isset($relation[$target]) && $relation[$target] == $onValue) {
                    $found[] = $relation;
                }
            } elseif(is_object($relation) && $relation->{$target} == $onValue) {
                $found[] = $relation;
            }
        }

        return $found;
    }
}