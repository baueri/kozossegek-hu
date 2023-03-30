<?php

namespace App\Migration;

use Phinx\Db\Adapter\AdapterInterface;
use Phinx\Db\Table;

class AppMigrationTable extends Table
{
    public function timestamps(bool $withDeletedAt = true): static
    {
        $this->createdAt();
        $this->timestamp('updated_at', ['null' => true]);

        if ($withDeletedAt) {
            $this->deletedAt();
        }

        return $this;
    }

    public function createdAt(string $column = 'created_at'): static
    {
        $this->timestamp($column, ['default' => 'CURRENT_TIMESTAMP']);

        return $this;
    }

    public function timestamp(string $column, array $options = []): static
    {
        $this->addColumn($column, AdapterInterface::PHINX_TYPE_DATETIME, $options);

        return $this;
    }

    public function deletedAt(): static
    {
        $this->timestamp('deleted_at', ['null' => true]);

        return $this;
    }

    public function updatedAt(string $column = 'updated_at', array $options = []): static
    {
        $this->timestamp($column, array_merge(['null' => true], $options));

        return $this;
    }

    public function date(string $column, array $options = []): static
    {
        $this->addColumn($column, AdapterInterface::PHINX_TYPE_DATE, $options);

        return $this;
    }

    public function enum(string $column, array $values, array $options = []): static
    {
        return $this->addColumn($column, 'enum', array_merge($options, compact('values')));
    }

    public function dropTimeStamps(): static
    {
        foreach (['created_at', 'updated_at', 'deleted_at'] as $column) {
            if ($this->hasColumn($column)) {
                $this->removeColumn($column);
            }
        }

        return $this;
    }
}
