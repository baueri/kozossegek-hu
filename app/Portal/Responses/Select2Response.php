<?php

namespace App\Portal\Responses;

use Framework\Model\ModelCollection;

/**
 * Description of Select2Response
 *
 * @author ivan
 */
abstract class Select2Response
{

    /**
     * @var ModelCollection
     */
    private $collection;

    public function __construct($collection)
    {
        if (is_array($collection)) {
            $collection = collect($collection);
        }

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
        return ['results' => $this->collection->map(function ($model) {
            return ['id' => $this->getId($model), 'text' => $this->getText($model)];
        })->toArray()];
    }
}
