<?php

namespace App\Migration;

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Table;

final class AppMigrationTable extends Table
{
    public function unsignedBigInteger(string $name, array $options = []): self
    {
        return $this->addColumn($name, AdapterInterface::PHINX_TYPE_BIG_INTEGER, array_merge($options, ['signed' => false]));
    }

    public function integer(string $name, array $options = []): AppMigrationTable
    {
        return $this->addColumn($name, AdapterInterface::PHINX_TYPE_INTEGER, $options);
    }

    public function string(string $name, array $options = []): self
    {
        return $this->addColumn($name, AdapterInterface::PHINX_TYPE_STRING, $options);
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

    public function createdAt(string $column = 'created_at'): self
    {
        $this->datetime($column, ['default' => 'CURRENT_TIMESTAMP']);

        return $this;
    }

    public function datetime(string $column, array $options = []): self
    {
        $this->addColumn($column, AdapterInterface::PHINX_TYPE_DATETIME, $options);

        return $this;
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

    public function date(string $column, array $options = []): self
    {
        $this->addColumn($column, AdapterInterface::PHINX_TYPE_DATE, $options);

        return $this;
    }

    public function enum(string $column, array $values, array $options = []): self
    {
        return $this->addColumn($column, 'enum', array_merge($options, compact('values')));
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
}
