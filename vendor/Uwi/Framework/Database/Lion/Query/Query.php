<?php

namespace Uwi\Database\Lion\Query;

use Uwi\Database\Connection;
use Uwi\Database\Lion\Model;
use Uwi\Database\Lion\Support\Collection;

class Query
{
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
    private string $modelClass;

    /**
     * Instantiate query
     */
    public function __construct(string $modelClass)
    {
        $this->connection = app()->singleton(Connection::class);
        $this->modelClass = $modelClass;
        $this->table = $modelClass::getTableName();
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
     * @return static
     */
    public function addWhere(string $columnName, ?string $operator = null, ?string $value = null): static
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
        ];

        return $this;
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
                $sql .= " and {$this->wheres[$i][0]} {$this->wheres[$i][1]} ?";
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
     * Exec query and get the result
     *
     * @return Collection|Model|null
     */
    public function get(): Collection|Model|null
    {
        $result = $this->exec();

        return match (count($result)) {
            0 => null,
            1 => new $this->modelClass($result[0]),
            default => Collection::make($result)->map(function ($el) {
                return new $this->modelClass($el);
            })
        };
    }
}
