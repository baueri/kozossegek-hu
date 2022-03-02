<?php

namespace App\Portal\Responses;

use Framework\Support\Collection;

/**
 * @template T
 */
abstract class Select2Response
{
    private Collection $collection;

    public function __construct($collection)
    {
        if (is_array($collection)) {
            $collection = collect($collection);
        }

        $this->collection = $collection;
    }

    /**
     * @param T $model
     * @return mixed
     */
    abstract public function getText($model);

    /**
     * @param T $model
     * @return mixed
     */
    public function getId($model)
    {
        return $this->getText($model);
    }

    public function __toString()
    {
        return json_encode($this->getResponse());
    }

    public function getResponse(): array
    {
        return ['results' => $this->collection->map(function ($model) {
            return ['id' => $this->getId($model), 'text' => $this->getText($model)];
        })->all()];
    }
}
