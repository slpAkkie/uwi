<?php

namespace Uwi\Services\Database\Lion\Query;

use Uwi\Services\Database\Lion\Contracts\BuilderContract;

class Builder implements BuilderContract
{
    /**
     * Query instance.
     *
     * @var \Uwi\Services\Database\Lion\Contracts\QueryContract
     */
    private \Uwi\Services\Database\Lion\Contracts\QueryContract $query;

    /**
     * Model Builder created for.
     *
     * @var \Uwi\Services\Database\Lion\Contracts\ModelContract|null
     */
    private \Uwi\Services\Database\Lion\Contracts\ModelContract|null $model;

    /**
     * Instantiate Builder.
     *
     * @param string $table
     * @param string $primaryKey
     * @param string|\Uwi\Services\Database\Lion\Contracts\ModelContract $model
     */
    public function __construct(
        string $table,
        string $primaryKey = 'id',
        mixed $model = null,
    ) {
        $this->model = isset($model) ? (gettype($model) === 'string' ? new $model() : $model) : null;
        $this->query = new Query($table, $primaryKey, $this->model ? $this->model::class : null);
    }

    /**
     * Returns record by the primary key value.
     *
     * @param integer|string $val
     * @return \Uwi\Services\Database\Lion\Contracts\ModelContract|null
     */
    public function find(int|string $val): \Uwi\Services\Database\Lion\Contracts\ModelContract|null
    {
        return $this->query->addWherePrimary('=', $val)->get()[0];
    }

    /**
     * Add where condition to the query.
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @param string $type
     * @return \Uwi\Services\Database\Lion\Contracts\BuilderContract
     */
    public function where(string $columnName, string|null $operator = null, string|null $value = null, string $type = 'and'): \Uwi\Services\Database\Lion\Contracts\BuilderContract
    {
        $this->query->addWhere($columnName, $operator, $value, $type);

        return $this;
    }

    /**
     * Add where condition to the query type as OR.
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @return \Uwi\Services\Database\Lion\Contracts\BuilderContract
     */
    public function orWhere(string $columnName, string|null $operator = null, string|null $value = null): \Uwi\Services\Database\Lion\Contracts\BuilderContract
    {
        return $this->where($columnName, $operator, $value, 'or');
    }

    /**
     * Check whether the record with provided primaryKey exists.
     *
     * @param string|null $primaryKey
     * @return boolean
     */
    public function exists(string|null $primaryKey = null): bool
    {
        return $this->query->exists($primaryKey);
    }

    /**
     * Save model to the database or update if it's already exists.
     *
     * @return boolean
     */
    public function save(): bool
    {
        $id = $this->query->insert($this->model->getDirty());

        return $id;
    }

    /**
     * Synchronize model data with database.
     *
     * @return boolean
     */
    public function update(): bool
    {
        if (!$this->model->isDirty()) {
            return false;
        }

        $this->query->update($this->model->primaryKey, $this->model->{$this->model->primaryKey}, $this->model->getDirty());

        return true;
    }

    /**
     * Delete record about model from the databse.
     *
     * @return boolean
     */
    public function delete(): bool
    {
        $this->query->delete();

        return true;
    }

    /**
     * Exec raw sql query with parameters.
     *
     * @param string $sql
     * @param array<string, mixed> $args
     * @return void
     */
    public function raw(string $sql, array $args = [])
    {
        return $this->query->execRaw($sql, $args);
    }

    /**
     * Exec query and get result.
     *
     * @param array|null $columns
     * @return array|Model|null
     */
    public function get(array|null $columns = null): array|\Uwi\Services\Database\Lion\Contracts\ModelContract|null
    {
        return $this->query->get($columns);
    }

    /**
     * Returns a sql string.
     *
     * @return string
     */
    public function toSql(): string
    {
        return $this->query->getDataToExec()[0];
    }

    /**
     * Dump a sql string and die.
     *
     * @return void
     */
    public function dd(): void
    {
        dd(...$this->query->getDataToExec());
    }
}
