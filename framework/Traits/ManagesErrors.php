<?php


namespace Framework\Traits;


use Framework\Support\Collection;

trait ManagesErrors
{
    /**
     * @var Collection
     */
    protected $errors;

    /**
     * ManagesErrors constructor.
     */
    private function get()
    {
        if (is_null($this->errors)) {
            $this->errors = new Collection();
        }

        return $this->errors;
    }

    public function hasErrors()
    {
        return $this->get()->isEmpty();
    }

    public function getErrors()
    {
        return $this->get();
    }

    public function fetchError()
    {
        return $this->get()->next();
    }

    public function pushError($error, $key = null)
    {
        $this->get()->set($key, $error);
    }
}