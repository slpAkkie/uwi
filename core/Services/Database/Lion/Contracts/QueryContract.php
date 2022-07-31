<?php

namespace Uwi\Services\Database\Lion\Contracts;

interface QueryContract
{
    /**
     * Instantiate Query.
     */
    public function __construct(string $table, string $primaryKey, string|null $model = null);

    /**
     * Exec query and get the result.
     *
     * @param array|null $columns
     * @return array
     */
    public function get(array|null $columns = null): array;

    /**
     * Returns if record with provided primary key exists.
     *
     * @param string $primaryKey
     * @return boolean
     */
    public function exists(string $primaryKey): bool;

    /**
     * Count records by column name.
     * Default by entire row.
     *
     * @param string $columnName
     * @return integer
     */
    public function count(string $columnName = '*'): int;

    /**
     * Insert new record into the table.
     *
     * @param array<string, mixed> $attributes
     * @return int|null
     */
    public function insert(array $attributes): int|null;

    /**
     * Update row by primary key with dirty fields.
     *
     * @param string $primaryKeyName
     * @param string $primaryKey
     * @param array<string, mixed> $attributes
     * @return boolean
     */
    public function update(string $primaryKeyName, string $primaryKey, array $attributes): bool;

    /**
     * Delete query.
     *
     * @return boolean
     */
    public function delete(): bool;

    /**
     * Add where condition to the query.
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @param string $type
     * @return \Uwi\Services\Database\Lion\Contracts\QueryContract
     */
    public function addWhere(string $columnName, string|null $operator = null, string|null $value = null, string $type = 'and'): \Uwi\Services\Database\Lion\Contracts\QueryContract;

    /**
     * Add where clause at primary key.
     *
     * @param string $operator
     * @param string $val
     * @return \Uwi\Services\Database\Lion\Contracts\QueryContract
     */
    public function addWherePrimary(string $operator, string $val): \Uwi\Services\Database\Lion\Contracts\QueryContract;

    /**
     * Get colums as a string.
     *
     * @return string
     */
    public function getColumns(): string;

    /**
     * Returns data for execution.
     *
     * @return array
     */
    public function getDataToExec(): array;

    /**
     * Exec raw sql query.
     *
     * @param string $sql
     * @param array<string, mixed> $parameters
     * @return array
     */
    public function execRaw(string $sql, array $parameters = []): array;
}
