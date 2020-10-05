<?php

namespace Framework\Database\Schema;

use Framework\Database\Schema\Action\{Action, AddColumn, ChangeColumn, RemoveColumn, AddIndex, RemoveIndex};

class Table
{
    use OptionTrait;

    /**
     * @var array
     */
    const DEFAULT_OPTIONS = [
        'collation' => 'utf8_general_ci',
        'charset' => 'utf8'
    ];

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var Action[]
     */
    protected $actions = [];

    /**
     * Table constructor.
     * @param string $tableName
     * @param array $options
     */
    public function __construct(string $tableName, array $options = [])
    {
        $this->tableName = $tableName;
        $this->setOptions(array_merge(self::DEFAULT_OPTIONS, $options));
    }

    /**
     * @param $tableName
     * @return Table
     */
    public static function make($tableName)
    {
        return new static($tableName);
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param null|string $type
     * @return Action[]
     */
    public function getActions($type = null)
    {
        if ($type) {
            return array_filter($this->actions, function($action) use ($type) {
                return $action instanceof $type;
            });
        }
        return $this->actions;
    }

    /**
     * @param $columns
     * @param array $options
     * @return static
     */
    public function addIndex($columns, $options = [])
    {
        return $this->addAction(new AddIndex($this, $columns, $options));
    }

    /**
     * @param $name
     * @param $type
     * @param $options
     * @return static
     */
    public function addColumn($name, $type, $options = [])
    {
        return $this->addAction(new AddColumn($this, $name, $type, $options));
    }

    /**
     * @param $name
     * @return Table
     */
    public function removeColumn($name)
    {
        return $this->addAction(new RemoveColumn($this, $name));
    }

    /**
     * @param $name
     * @param $type
     * @param array $options
     * @return Table
     */
    public function changeColumn($name, $type, $options = [])
    {
        return $this->addAction(new ChangeColumn($this, $name, $type, $options));
    }

    /**
     * @param $name
     * @return Table
     */
    public function removeIndex($name)
    {
        return $this->addAction(new RemoveIndex($this, $name));
    }

    /**
     * @param Action $action
     * @return $this
     */
    public function addAction(Action $action)
    {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function create()
    {
        $query = 'CREATE TABLE IF NOT EXISTS ' . $this->tableName . '(';

        $columns = $this->getActions(AddColumn::class);
        $primaryKey = '';
        $query .= implode(', ', array_map(function($action, &$primaryKey){
            /* @var $action AddColumn */
            if ($action->getOption('primary')) {
                $primaryKey = ', PRIMARY KEY (' . $action->getName() . ')';
            }
            return $action->getCommand();
            }, $columns, [&$primaryKey]));

        $query .= $primaryKey . ')';
        $query .= sprintf(' CHARACTER SET %s COLLATE %s', $this->getOption('charset'), $this->getOption('collation'));

        db()->query($query);
    }

    /**
     * @throws \Exception
     */
    public function save()
    {
        $query = 'ALTER TABLE ' . $this->tableName;

        $query .= implode('; ', array_map(function($action){
            return 'ADD ' . $action->getCommand();
        }, $this->getActions(AddColumn::class))) . ';';

        $query .= implode('; ', array_map(function($action){
            return $action->getCommand();
        }, $this->getActions(RemoveColumn::class))) . ';';

        db()->query($query);
    }

    public function drop()
    {
        db()->query('DROP TABLE IF EXISTS ' . $this->tableName);
    }
}
