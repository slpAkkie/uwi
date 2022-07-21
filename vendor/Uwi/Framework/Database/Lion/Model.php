<?php

namespace Uwi\Database\Lion;

use ReflectionClass;
use Uwi\Database\Connection;
use Uwi\Database\Lion\Query\Builder;
use Uwi\Exceptions\Exception;
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
    protected array $originals = [];

    /**
     * Array of changed columns
     *
     * @var array
     */
    protected array $dirty = [];

    /**
     * Column allowed for mass-assignment
     *
     * @var array
     */
    protected array $fillable;

    /**
     * Indicate if the model is already exists
     *
     * @var boolean
     */
    protected bool $exists;

    /**
     * Instantiate model
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [], bool $preserveFillable = false)
    {
        $this->loadSchema();

        $primaryKey = key_exists($this->primaryKey, $attributes) ? $attributes[$this->primaryKey] : null;

        if ($primaryKey) {
            $this->exists = static::exists($primaryKey);
        } else {
            $this->exists = false;
        }

        foreach ($attributes as $column => $val) {
            if (!in_array($column, $this->attributes)) {
                throw new Exception('Column [' . $column . '] doesn\'t exists in this model');
            }
            if (!$preserveFillable && isset($this->fillable) && !in_array($column, $this->fillable)) {
                throw new Exception('Column [' . $column . '] doesn\'t allowed for mass-assignment');
            }

            if ($this->exists) {
                $this->originals[$column] = $val;
            } else {
                $this->dirty[$column] = $val;
            }
        }

        foreach ($this->attributes as $column) {
            if (!key_exists($column, $this->originals)) {
                $this->originals[$column] = null;
            }
        }
    }

    /**
     * Load table schema
     *
     * @return void
     */
    private function loadSchema(): void
    {
        $this->attributes = (new Builder('information_schema.COLUMNS'))
            ->where('TABLE_SCHEMA', config('database.connection.dbname'))
            ->where('TABLE_NAME', $this->getTableName())
            ->get(['COLUMN_NAME'])->map(fn ($item) => $item['COLUMN_NAME'])->toArray();
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
     * Get dirty columns and it's values
     *
     * @return array
     */
    public function getDirty(): array
    {
        return $this->dirty;
    }

    /**
     * Indicate if there are not saved data in the model
     *
     * @return boolean
     */
    public function isDirty(): bool
    {
        return !!count($this->dirty);
    }

    /**
     * Instantiate new builder object
     *
     * @return Builder
     */
    public function newQuery(): Builder
    {
        return new Builder($this->getTableName(), $this->primaryKey, $this);
    }

    /**
     * Save model to the database
     *
     * @return boolean
     */
    public function save(): bool
    {
        if ($this->exists) {
            return $this->newQuery()->update();
        }

        $this->newQuery()->save();
        $id = tap(fn (Connection $connection) => $connection->lastInsertedID());
        if (!$id) {
            return false;
        }

        $this->originals = array_merge($this->originals, $this->dirty);
        $this->dirty = [];
        $this->originals[$this->primaryKey] = $id;
        $this->exists = true;

        return true;
    }

    /**
     * Update model with provided data
     *
     * @param array|null $attributes
     * @return boolean
     */
    public function update(?array $attributes = []): bool
    {
        if (!$this->exists) {
            return false;
        }

        $this->dirty = $attributes;

        return count($this->dirty) ? $this->newQuery()->update() : false;
    }

    /**
     * Delete model from database
     *
     * @return boolean
     */
    public function delete(): bool
    {
        static::where($this->primaryKey, $this->originals[$this->primaryKey])->delete();

        $this->dirty = array_merge($this->originals, $this->dirty);
        $this->originals = [];
        $this->exists = false;

        return true;
    }

    /**
     * Get the model column value
     *
     * @param $key
     * @return mixed
     */
    public function __get($key): mixed
    {
        if (property_exists($this::class, $key)) {
            return $this->$key;
        }

        if (!in_array($key, $this->attributes)) {
            throw new Exception('Property [' . $key . '] doesn\'t exists in this model');
        }

        return key_exists($key, $this->dirty)
            ? $this->dirty[$key]
            : (key_exists($key, $this->originals)
                ? $this->originals[$key]
                : null);
    }

    /**
     * Set the model column value
     *
     * @param $key
     * @param $val
     */
    public function __set($key, $val)
    {
        if (property_exists($this::class, $key)) {
            return $this->$key = $val;
        }

        if (!in_array($key, $this->attributes)) {
            throw new Exception('Property [' . $key . '] doesn\'t exists in this model');
        }

        return $this->dirty[$key] = $val;
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
