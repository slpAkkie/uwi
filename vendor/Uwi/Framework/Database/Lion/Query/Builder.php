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
     * Model class to get
     *
     * @var string
     */
    private string $modelClass;

    /**
     * Instantiate Builder
     *
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;

        $this->query = new Query($modelClass);
    }

    /**
     * Returns record by the primary key value
     *
     * @param integer|string $val
     * @return Model
     */
    public function find(int|string $val): Model
    {
        return $this->query->addWhere($this->modelClass::getPrimaryKey(), '=', $val)->get();
    }

    /**
     * Add where condition to the query
     *
     * @param string $columnName
     * @param string|null $operator
     * @param string|null $value
     * @return static
     */
    public function where(string $columnName, ?string $operator = null, ?string $value = null): static
    {
        $this->query->addWhere($columnName, $operator, $value);

        return $this;
    }

    /**
     * Undocumented function
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
