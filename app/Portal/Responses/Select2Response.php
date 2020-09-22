<?php

namespace App\Portal\Responses;
/**
 * Description of Select2Response
 *
 * @author ivan
 */
abstract class Select2Response {

    /**
     * @var \Framework\Database\Repository\ModelCollection
     */
    private $collection;

    private $withReset;
    
    public function __construct(\Framework\Database\Repository\ModelCollection $collection) {
        $this->collection = $collection;
    }
    
    abstract public function getText($model);
    
    public function getId($model)
    {
        return $this->getText($model);
    }

    public function __toString()
    {
        return json_encode($this->getResponse());
    }
    
    
    public function getResponse()
    {
        return ['results' => $this->collection->map(function($model){
            return ['id' => $this->getId($model), 'text' => $this->getText($model)];
        })->toArray()];
    }
}
