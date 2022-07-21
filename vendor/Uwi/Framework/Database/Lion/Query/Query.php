<?php

namespace Uwi\Database\Lion\Query;

use Uwi\Database\Connection;
use Uwi\Database\Lion\Support\Collection;

class Query
{
    /**
     * Connection to execute query on
     *
     * @var Connection
     */
    private Connection $connection;

    /**
     * Table t oexecute query at
     *
     * @var string
     */
    private string $table;

    /**
     * Model that returns as Query result
     *
     * @var string
     */
    private string $model;

    /**
     * Table primary key
     *
     * @var string
     */
    private string $primaryKey;

    /**
     * Which command shoud be executed
     * Such as SELECT or something else
     *
     * @var string
     */
    private string $command;

    /**
     * Array of columns
     *
     * @var array
     */
    private array $columns = ['*'];

    /**
     * Array of where conditions
     *
     * @var array
     */
    private array $wheres = [];

    /**
     * Instantiate query
     */
    public function __construct(string $table, string $primaryKey, string $model)
    {
        $this->connection = app()->singleton(Connection::class);
        $this->model = $model;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Set command for the query
     *
     * @param string $command
     * @return static
     */
    public function setCommand(string $command): static
    {
        if (!isset($this->command)) {
            $this->command = $command;
        }

        return $this;
    }

    /**
     * Add where condition to the query
     *
     * @param string $columnName
     * @param ?string $operator
     * @param ?string $value
     * @param string $type
     * @return static
     */
    public function addWhere(string $columnName, ?string $operator = null, ?string $value = null, string $type = 'and'): static
    {
        $this->setCommand('select');

        if ($operator === null && $value !== null) {
            $operator = '=';
        } else if ($value === null && $operator !== null) {
            $value = $operator;
            $operator = '=';
        } else if ($operator === null && $value === null) {
            $operator = 'is not';
            $value = 'NULL';
        }

        $this->wheres[] = [
            $columnName,
            $operator,
            $value,
            $type,
        ];

        return $this;
    }

    /**
     * Add where clause at primary key
     *
     * @param string $operator
     * @param string $val
     * @return static
     */
    public function addWherePrimary(string $operator, string $val): static
    {
        return $this->addWhere($this->primaryKey, $operator, $val);
    }

    /**
     * Get colums as a string
     *
     * @return string
     */
    private function getColumns(): string
    {
        return join($this->columns);
    }

    /**
     * Returns a sql string
     *
     * @return string
     */
    public function getQueryString(): string
    {
        $sql = "{$this->command} {$this->getColumns()} from {$this->table}";

        for ($i = 0; $i < count($this->wheres); $i++) {
            if ($i === 0) {
                $sql .= " where {$this->wheres[$i][0]} {$this->wheres[$i][1]} ?";
            } else {
                $sql .= " {$this->wheres[$i][3]} {$this->wheres[$i][0]} {$this->wheres[$i][1]} ?";
            }
        }

        return $sql;
    }

    /**
     * Returns an array of parameters for conditions
     *
     * @return array
     */
    public function getParameters(): array
    {
        return array_reduce($this->wheres, function ($carry, $item) {
            $carry[] = $item[2];

            return $carry;
        }, []);
    }

    /**
     * Exec the query
     *
     * @return array
     */
    private function exec(): array
    {
        return $this->connection->exec($this->getQueryString(), $this->getParameters());
    }

    /**
     * Exec raw sql query
     *
     * @param string $sql
     * @param array $parameters
     * @return Collection
     */
    public function execRaw(string $sql, array $parameters = []): Collection
    {
        $result = $this->connection->exec($sql, $parameters);

        return $this->wrapResult($result);
    }

    /**
     * Wrap result into models in collection
     *
     * @param array $result
     * @return Collection
     */
    private function wrapResult(array $result = []): Collection
    {
        return Collection::make($result)->map(function ($el) {
            return new $this->model($el);
        });
    }

    /**
     * Exec query and get the result
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $result = $this->exec();

        return $this->wrapResult($result);
    }
}
