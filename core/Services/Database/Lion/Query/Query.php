<?php

namespace Uwi\Services\Database\Lion\Query;

use Uwi\Contracts\Database\ConnectionContract;
use Uwi\Services\Database\Lion\Contracts\QueryContract;
use Uwi\Services\Database\Lion\Grammar\DeleteGrammar;
use Uwi\Services\Database\Lion\Grammar\InsertGrammar;
use Uwi\Services\Database\Lion\Grammar\SelectGrammar;
use Uwi\Services\Database\Lion\Grammar\UpdateGrammar;

class Query implements QueryContract
{
    /**
     * Connection to execute query on.
     *
     * @var ConnectionContract
     */
    public ConnectionContract $connection;

    protected const GRAMMARS = [
        'select' => SelectGrammar::class,
        'update' => UpdateGrammar::class,
        'delete' => DeleteGrammar::class,
        'insert' => InsertGrammar::class,
    ];

    /**
     * Table t oexecute query at.
     *
     * @var string
     */
    public string $table;

    /**
     * Model that returns as Query result.
     *
     * @var ?string
     */
    public ?string $model;

    /**
     * Table primary key.
     *
     * @var string
     */
    public string $primaryKey;

    /**
     * Which type shoud be executed.
     * Such as SELECT or something else.
     *
     * @var string
     */
    public string $type;

    /**
     * Array of columns.
     *
     * @var array
     */
    public array $columns = ['*'];

    /**
     * Array of where conditions.
     *
     * @var array
     */
    public array $wheres = [];

    /**
     * Props for grammar processor.
     *
     * @var array
     */
    public array $grammarProps = [];

    /**
     * Instantiate new Query instance.
     */
    public function __construct(string $table, string $primaryKey, ?string $model = null)
    {
        $this->connection = app()->singleton(ConnectionContract::class);
        $this->model = $model;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    /**
     * Set type for the query.
     *
     * @param string $type
     * @return static
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set type for the query only if it hasn't been setted yet.
     *
     * @param string $type
     * @return void
     */
    public function setTypeIfNot(string $type)
    {
        if (!isset($this->type)) {
            $this->setType($type);
        }

        return $this;
    }

    /**
     * Set new select statement.
     *
     * @param array|null $columns
     * @return static
     */
    public function select(?array $columns = null): static
    {
        if ($columns && count($columns)) {
            $this->columns = $columns;
        }

        $this->setType('select');

        return $this;
    }

    /**
     * Returns if record with provided primary key exists.
     *
     * @param string $primaryKey
     * @return boolean
     */
    public function exists(string $primaryKey): bool
    {
        return !!$this->addWherePrimary('=', $primaryKey)->count('1');
    }

    /**
     * Count records by column name.
     * Default by entire row.
     *
     * @param string $columnName
     * @return integer
     */
    public function count(string $columnName = '*'): int
    {
        return $this->getNude(["COUNT({$columnName}) as aggregate"])[0]['aggregate'];
    }

    /**
     * Insert new record into the table.
     *
     * @param array $attributes
     * @return ?int
     */
    public function insert(array $attributes): ?int
    {
        $this->setType('insert');
        $this->grammarProps = $attributes;

        $this->exec();

        return $this->connection->lastInsertedId();
    }

    /**
     * Update row by primary key with dirty fields.
     *
     * @param string $primaryKey
     * @param array $attributes
     * @return boolean
     */
    public function update(string $primaryKeyName, string $primaryKey, array $attributes): bool
    {
        $this->setType('update');
        $this->grammarProps = [
            'primaryKeyName' => $primaryKeyName,
            'primaryKey' => $primaryKey,
            'props' => $attributes
        ];

        $this->exec();

        return true;
    }

    /**
     * Delete query.
     *
     * @return boolean
     */
    public function delete(): bool
    {
        $this->setType('delete');

        $this->exec();

        return true;
    }

    /**
     * Add where condition to the query.
     *
     * @param string $columnName
     * @param ?string $operator
     * @param ?string $value
     * @param string $type
     * @return static
     */
    public function addWhere(string $columnName, ?string $operator = null, ?string $value = null, string $type = 'and'): static
    {
        $this->setTypeIfNot('select');

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
     * Add where clause at primary key.
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
     * Get colums as a string.
     *
     * @return string
     */
    public function getColumns(): string
    {
        return join($this->columns);
    }

    /**
     * Returns data for execution.
     *
     * @return array
     */
    public function getDataToExec(): array
    {
        /** @var Grammar */
        $grammar = new (self::GRAMMARS[$this->type])($this, $this->grammarProps);
        return $grammar->get();
    }

    /**
     * Exec the query.
     *
     * @return array
     */
    private function exec(): array
    {
        return $this->connection->exec(...$this->getDataToExec());
    }

    /**
     * Exec raw sql query.
     *
     * @param string $sql
     * @param array $parameters
     * @return array
     */
    public function execRaw(string $sql, array $parameters = []): array
    {
        $result = $this->connection->exec($sql, $parameters);

        return $this->wrapResult($result);
    }

    /**
     * Wrap result into models in array.
     *
     * @param array $result
     * @return array
     */
    private function wrapResult(array $result = []): array
    {
        return $this->model ? array_map(function ($el) {
            return new $this->model($el, true);
        }, $result) : $result;
    }

    /**
     * Exec query and get the result.
     *
     * @param ?array $columns
     * @return array
     */
    public function get(?array $columns = null): array
    {
        if ($columns) {
            $this->columns = $columns;
        }

        $result = $this->exec();

        return $this->wrapResult($result);
    }

    /**
     * Exec query and get the result without wrapping into array and model.
     *
     * @param array|null $columns
     * @return array
     */
    public function getNude(?array $columns = null): array
    {
        if ($columns) {
            $this->columns = $columns;
        }

        return $this->connection->exec(...$this->getDataToExec());
    }
}
