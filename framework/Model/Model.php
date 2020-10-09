<?php


namespace Framework\Model;


abstract class Model
{
    /**
     * @var mixed
     */
    public $id;

    /**
     * @var string
     */
    protected static $primaryCol = 'id';

    /**
     * @var mixed[]
     */
    protected $originalValues = [];

    /**
     * Model constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {

        $this->setProperties($values);

        $this->originalValues = $values;
    }

    /**
     * @return string
     */
    public static function getPrimaryCol()
    {
        return static::$primaryCol;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->{static::$primaryCol};
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return (bool) $this->getId();
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->{static::$primaryCol} = $id;
    }

    /**
     * @return array|mixed[]
     */
    public function getOriginalValues()
    {
        return $this->originalValues;
    }

    /**
     * @param static $model
     * @return bool
     */
    public function is($model)
    {
        return $model instanceof $this && $this->getId() == $model->getId();
    }

    /**
     *
     * @param array $data
     */
    public function update(array $data)
    {
        $this->setProperties($data);
    }

    /**
     *
     * @param array $values
     * @return static
     */
    protected function setProperties(array $values)
    {
        foreach ($values as $col => $value) {
            if (property_exists($this, $col)) {
                $this->{$col} = $value;
            }
        }

        return $this;
    }
}
