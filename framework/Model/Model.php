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
        foreach ($values as $col => $value) {
            if (property_exists($this, $col)) {
                $this->{$col} = $value;
            }
        }

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
     * @param $id
     * @return bool
     */
    public function is($id)
    {
        return $this->getId() == $id;
    }

}