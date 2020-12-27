<?php


namespace Framework\Traits;


use Framework\Support\Collection;

trait ManagesErrors
{
    /**
     * @var Collection|null
     */
    protected ?Collection $errors = null;

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
        return $this->get()->isNotEmpty();
    }

    public function getErrors()
    {
        return $this->get();
    }

    public function fetchError()
    {
        return $this->get()->next();
    }

    public function setError($error, $key = null)
    {
        $this->get()->set($key, $error);
    }

    public function pushError($error, $key = null)
    {
        $this->get()->push($error, $key);
    }
}
