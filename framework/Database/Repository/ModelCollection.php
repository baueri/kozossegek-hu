<?php


namespace Framework\Database\Repository;


use Framework\Support\Collection;

class ModelCollection extends Collection
{
    /**
     * 
     * @param string $localAttribute
     * @param string $remoteAttribute
     * @param mixed  $relations
     * @return static
     */
    public function with($relations, $toLoad, $on, $target = 'id')
    {
        foreach ($this->items as $item) {
            $item->{$toLoad} = static::getAttributeValue($relations, $target, $item->{$on});
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
    private static function getAttributeValue($relations, $target, $onValue)
    {
        foreach ($relations as $relation) {
            if (is_array($relation)) {
                if (isset($relation[$target]) && $relation[$target] == $onValue) {
                    return $relation;
                }
            } elseif(is_object($relation) && $relation->{$target} == $onValue) {
                return $relation->{$target};
            }
        }
        
        return null;
    }
}