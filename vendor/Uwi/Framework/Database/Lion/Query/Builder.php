<?php

namespace Uwi\Database\Lion\Query;

use Uwi\Database\Lion\Model;
use Uwi\Database\Lion\Support\Collection;

class Builder
{
    /**
     * Query
     *
     * @var Query
     */
    private Query $query;

    /**
     * Instantiate Builder
     *
     * @param string $table
     * @param string $primaryKey
     * @param string|null $model
     */
    public function __construct(string $table, string $primaryKey, ?string $model = null)
    {
        if ($model === null) {
            $model = Model::class;
        }

        $this->query = new Query($table, $primaryKey, $model);
    }

    /**
     * Returns record by the primary key value
     *
     * @param integer|string $val
     * @return Model
     */
    public function find(int|string $val): Model
    {
        return $this->query->addWherePrimary('=', $val)->get()[0];
    }

    /**
     * Add where condition to the query
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @param string $type
     * @return static
     */
    public function where(string $columnName, ?string $operator = null, ?string $value = null, string $type = 'and'): static
    {
        $this->query->addWhere($columnName, $operator, $value, $type);

        return $this;
    }

    /**
     * Add where condition to the query type as OR
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @return static
     */
    public function orWhere(string $columnName, ?string $operator = null, ?string $value = null): static
    {
        return $this->where($columnName, $operator, $value, 'or');
    }

    /**
     * Exec raw sql query with parameters
     *
     * @param string $sql
     * @param array $args
     * @return void
     */
    public function raw(string $sql, array $args = [])
    {
        return $this->query->execRaw($sql, $args);
    }

    /**
     * Exec query and get result
     *
     * @return Collection|Model|null
     */
    public function get(): Collection|Model|null
    {
        return $this->query->get();
    }

    /**
     * Returns a sql string
     *
     * @return string
     */
    public function toSql(): string
    {
        return $this->query->getQueryString();
    }
}
