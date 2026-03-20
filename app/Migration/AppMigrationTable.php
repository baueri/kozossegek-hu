<?php

namespace App\Migration;

use Framework\Exception\MethodNotFoundException;
use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Table;

/**
 * 
 * @method static integer(string $name, array $options = [])
 * @method static string(string $name, array $options = [])
 * @method static text(string $name, array $options = [])
 * @method static datetime(string $name, array $options = [])
 * @method static date(string $name, array $options = [])
 */
final class AppMigrationTable extends Table
{
    protected const MAP = [
        'integer' => AdapterInterface::PHINX_TYPE_INTEGER,
        'text' => AdapterInterface::PHINX_TYPE_TEXT,
        'string' => AdapterInterface::PHINX_TYPE_STRING,
        'date' => AdapterInterface::PHINX_TYPE_DATE,
        'datetime' => AdapterInterface::PHINX_TYPE_DATETIME,
    ];

    public function unsignedBigInteger(string $name, array $options = []): self
    {
        return $this->addColumn($name, AdapterInterface::PHINX_TYPE_BIG_INTEGER, array_merge($options, ['signed' => false]));
    }

    public function datetimes(bool $withDeletedAt = true): self
    {
        $this->createdAt();
        $this->datetime('updated_at', ['null' => true]);

        if ($withDeletedAt) {
            $this->deletedAt();
        }

        return $this;
    }

    public function createdAt(string $column = 'created_at', ): self
    {
        $this->datetime($column, ['default' => 'CURRENT_TIMESTAMP']);

        return $this;
    }

    public function __call($method, $arguments)
    {
        if (isset(self::MAP[$method])) {
            return $this->addColumn(array_shift($arguments), self::MAP[$method], $arguments[0] ?? []);
        }

        $class = get_class($this);
        throw new MethodNotFoundException("method {$class}::{$method} not found");
    }

    public function deletedAt(): self
    {
        $this->datetime('deleted_at', ['null' => true]);

        return $this;
    }

    public function updatedAt(string $column = 'updated_at', array $options = []): self
    {
        $this->datetime($column, array_merge(['null' => true], $options));

        return $this;
    }

    public function enum(string $column, array $values, array $options = []): self
    {
        return $this->addColumn($column, AdapterInterface::PHINX_TYPE_ENUM, array_merge($options, compact('values')));
    }

    public function dropTimeStamps(): self
    {
        foreach (['created_at', 'updated_at', 'deleted_at'] as $column) {
            if ($this->hasColumn($column)) {
                $this->removeColumn($column);
            }
        }

        return $this;
    }

    public function unique($columns, array $options = []): self
    {
        return $this->addIndex($columns, array_merge($options, ['unique' => true]));
    }
}
