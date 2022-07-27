<?php

namespace Uwi\Services\Database\Lion\Contracts;

interface QueryContract
{
    /**
     * Instantiate new Query instance.
     */
    public function __construct(string $table, string $primaryKey, ?string $model = null);

    /**
     * Exec query and get the result.
     *
     * @param ?array $columns
     * @return array
     */
    public function get(?array $columns = null): array;

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
     * @param array $attributes
     * @return ?int
     */
    public function insert(array $attributes): ?int;

    /**
     * Update row by primary key with dirty fields.
     *
     * @param string $primaryKey
     * @param array $attributes
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
     * @param ?string $operator
     * @param ?string $value
     * @param string $type
     * @return static
     */
    public function addWhere(string $columnName, ?string $operator = null, ?string $value = null, string $type = 'and'): static;

    /**
     * Add where clause at primary key.
     *
     * @param string $operator
     * @param string $val
     * @return static
     */
    public function addWherePrimary(string $operator, string $val): static;

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
     * @param array $parameters
     * @return array
     */
    public function execRaw(string $sql, array $parameters = []): array;
}
