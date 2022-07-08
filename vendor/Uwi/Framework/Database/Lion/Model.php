<?php

namespace Uwi\Database\Lion;

use ReflectionClass;
use Uwi\Database\Lion\Query\Builder;
use Uwi\Support\Str;

class Model
{
    /**
     * Table relative to the model
     *
     * @var string|null
     */
    protected ?string $table = null;

    /**
     * Model's primary key
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Array of columns
     *
     * @var array
     */
    protected array $attributes;

    /**
     * Array of original values of columns
     *
     * @var array
     */
    protected array $originals;

    /**
     * Instantiate model
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $column => $val) {
            $this->attributes[] = $column;
            $this->originals[$column] = $val;
        }
    }

    /**
     * Get model's table name
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table ?? $this->getQualifiedTableName();
    }

    /**
     * Get model's table name depending on model's name
     *
     * @return string
     */
    public function getQualifiedTableName(): string
    {
        return Str::lower(
            Str::plural(
                (new ReflectionClass(static::class))->getShortName()
            )
        );
    }

    /**
     * Instantiate new builder object
     *
     * @return Builder
     */
    public function newQuery(): Builder
    {
        return new Builder($this->getTableName(), $this->primaryKey, static::class);
    }

    /**
     * Call Buidler methods
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->newQuery()->{$name}(...$arguments);
    }

    /**
     * Handle static calls
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        return (new static())->{$name}(...$arguments);
    }
}
