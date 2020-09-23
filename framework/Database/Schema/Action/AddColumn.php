<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 10/11/18
 * Time: 11:45
 */

namespace Framework\Database\Schema\Action;

use Framework\Database\Schema\OptionTrait;
use Framework\Database\Schema\Table;

class AddColumn extends Action
{
    use OptionTrait;

    protected $name;

    protected $type;


    public function __construct(Table $table, $name, $type, $options)
    {
        parent::__construct($table);

        $this->name = $name;
        $this->type = $type == 'primary' ? 'INT' : $type;
        $this->setOptions($options);

        if ($type == 'primary') {
            $this->options['primary'] = true;
        }

    }

    public function getCommand()
    {
        return $this->name . ' ' . $this->buildType() . $this->buildCommandParams();
    }

    private function buildType()
    {
        if ($this->getOption('primary')) {
            return 'INT(11) NOT NULL AUTO_INCREMENT';
        }
        return $this->type;
    }

    private function buildCommandParams()
    {
        $out = '';
        if (isset($this->options['length'])) {
            $out .= '(' . $this->options['length'] . ')';
        }

        if ($this->hasOption('values') && $this->type == 'ENUM') {
            $out .= '("' . implode('","', (array)$this->getOption('values')) . '")';
        }

        if ($this->hasOption('collation')) {
            $out .= 'COLLATE ' . $this->getOption('collation');
        }

        if ($this->hasOption('default')) {
            $default = $this->getOption('default');
            $out .= ' DEFAULT ' . ($default === null || $default === 'NULL' ? $default : '"' . $default . '"');
        }

        if ($this->hasOption('auto_increment') && $this->getOption('auto_increment') == true) {
            $out .= ' AUTO_INCREMENT';
        }

        return $out;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }
}