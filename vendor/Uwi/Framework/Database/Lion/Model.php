<?php

namespace Uwi\Database\Lion;

use ReflectionClass;
use Uwi\Database\Lion\Query\Builder;
use Uwi\Exceptions\NotFoundException;
use Uwi\Support\Str;

class Model
{
    /**
     * Table relative to the model
     *
     * @var string|null
     */
    protected static ?string $table = null;

    /**
     * Model's primary key
     *
     * @var string
     */
    protected static string $primaryKey = 'id';

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
     * Get model's primary key
     *
     * @return string
     */
    public static function getPrimaryKey(): string
    {
        return static::$primaryKey;
    }

    /**
     * Get model's table name
     *
     * @return string
     */
    public static function getTableName(): string
    {
        return static::$table ?? static::getQualifiedTableName();
    }

    /**
     * Get model's table name depending on model's name
     *
     * @return string
     */
    public static function getQualifiedTableName(): string
    {
        return Str::lower(
            Str::plural(
                (new ReflectionClass(static::class))->getShortName()
            )
        );
    }

    /**
     * Call Buidler methods
     *
     * @param [type] $name
     * @param [type] $arguments
     * @return void
     */
    public static function __callStatic($name, $arguments)
    {
        if (!method_exists(Builder::class, $name)) {
            throw new NotFoundException('Method [' . $name . '] not found on Builder');
        }

        $builder = new Builder(static::class);

        return $builder->{$name}(...$arguments);
    }
}
