<?php

namespace Uwi\Services\Database\Lion\Contracts;

interface BuilderContract
{
    /**
     * Instantiate Builder.
     *
     * @param string $table
     * @param string $primaryKey
     * @param string|\Uwi\Services\Database\Lion\Contracts\ModelContract $model
     */
    public function __construct(string $table, string $primaryKey = 'id', mixed $model = null);

    /**
     * Returns record by the primary key value.
     *
     * @param integer|string $val
     * @return \Uwi\Services\Database\Lion\Contracts\ModelContract|null
     */
    public function find(int|string $val): \Uwi\Services\Database\Lion\Contracts\ModelContract|null;

    /**
     * Add where condition to the query.
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @param string $type
     * @return \Uwi\Services\Database\Lion\Contracts\BuilderContract
     */
    public function where(string $columnName, string|null $operator = null, string|null $value = null, string $type = 'and'): \Uwi\Services\Database\Lion\Contracts\BuilderContract;

    /**
     * Add where condition to the query type as OR.
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @return \Uwi\Services\Database\Lion\Contracts\BuilderContract
     */
    public function orWhere(string $columnName, string|null $operator = null, string|null $value = null): \Uwi\Services\Database\Lion\Contracts\BuilderContract;

    /**
     * Check whether the record with provided primaryKey exists.
     *
     * @param string|null $primaryKey
     * @return boolean
     */
    public function exists(string|null $primaryKey = null): bool;

    /**
     * Save model to the database or update if it's already exists.
     *
     * @return boolean
     */
    public function save(): bool;

    /**
     * Synchronize model data with database.
     *
     * @return boolean
     */
    public function update(): bool;

    /**
     * Delete record about model from the databse.
     *
     * @return boolean
     */
    public function delete(): bool;

    /**
     * Exec raw sql query with parameters.
     *
     * @param string $sql
     * @param array<string, mixed> $args
     * @return void
     */
    public function raw(string $sql, array $args = []);

    /**
     * Exec query and get result.
     *
     * @param array|null $columns
     * @return array|Model|null
     */
    public function get(array|null $columns = null): array|\Uwi\Services\Database\Lion\Contracts\ModelContract|null;

    /**
     * Returns a sql string.
     *
     * @return string
     */
    public function toSql(): string;

    /**
     * Dump a sql string and die.
     *
     * @return void
     */
    public function dd(): void;
}
