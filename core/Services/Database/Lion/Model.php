<?php

namespace Uwi\Services\Database\Lion;

use Exception;
use ReflectionClass;
use Uwi\Contracts\Database\ConnectionContract;
use Uwi\Services\Database\Lion\Contracts\BuilderContract;
use Uwi\Services\Database\Lion\Contracts\ModelContract;
use Uwi\Support\Str;

class Model implements ModelContract
{
    /**
     * Table relative to the model.
     *
     * @var string|null
     */
    protected string|null $table = null;

    /**
     * Model's primary key.
     *
     * @var string
     */
    protected string $primaryKey = 'id';

    /**
     * Array of columns.
     *
     * @var array<string>
     */
    protected array $attributes;

    /**
     * Array of original values of columns.
     *
     * @var array<string, mixed>
     */
    protected array $originals = [];

    /**
     * Array of changed columns.
     *
     * @var array<string, mixed>
     */
    protected array $dirty = [];

    /**
     * Column allowed for mass-assignment.
     *
     * @var array<string>
     */
    protected array $fillable;

    /**
     * Indicate if the model is already exists.
     *
     * @var boolean
     */
    protected bool $exists;

    /**
     * Instantiate Model.
     *
     * @param array<string, mixed> $attributes
     * 
     * @throws \Uwi\Foundation\Exceptions\Exception
     */
    public function __construct(
        array $attributes = [],
        bool $preserveFillable = false,
    ) {
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
     * Load table schema.
     *
     * @return void
     */
    private function loadSchema(): void
    {
        $this->attributes = array_map(fn ($item) => $item['COLUMN_NAME'], app()->make(BuilderContract::class, 'information_schema.COLUMNS')
            ->where('TABLE_SCHEMA', env('DATABASE_NAME'))
            ->where('TABLE_NAME', $this->getTableName())
            ->get(['COLUMN_NAME']));
    }

    /**
     * Get model's table name.
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->table ?? $this->getQualifiedTableName();
    }

    /**
     * Get model's table name depending on model's name.
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
     * Get dirty columns and it's values.
     *
     * @return array<string, mixed>
     */
    public function getDirty(): array
    {
        return $this->dirty;
    }

    /**
     * Indicate if there are not saved data in the model.
     *
     * @return boolean
     */
    public function isDirty(): bool
    {
        return !!count($this->dirty);
    }

    /**
     * Instantiate new Builder.
     *
     * @return BuilderContract
     */
    public function newQuery(): BuilderContract
    {
        return app()->make(BuilderContract::class, $this->getTableName(), $this->primaryKey, $this);
    }

    /**
     * Save model to the database.
     *
     * @return boolean
     */
    public function save(): bool
    {
        if ($this->exists) {
            return $this->newQuery()->update();
        }

        $this->newQuery()->save();
        $id = tap(fn (ConnectionContract $connection) => $connection->lastInsertedId());
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
     * Update model with provided data.
     *
     * @param array<string, mixed> $attributes
     * @return boolean
     */
    public function update(array $attributes = []): bool
    {
        if (!$this->exists) {
            return false;
        }

        $this->dirty = $attributes;

        return count($this->dirty) ? $this->newQuery()->update() : false;
    }

    /**
     * Delete model from database.
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
     * Get the model column value.
     *
     * @param string $key
     * @return mixed
     * 
     * @throws \Uwi\Foundation\Exceptions\Exception
     */
    public function __get(string $key): mixed
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
     * Set the model column value.
     *
     * @param string $key
     * @param mixed $val
     * @return mixed
     * 
     * @throws \Uwi\Foundation\Exceptions\Exception
     */
    public function __set(string $key, mixed $val)
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
     * Call Buidler methods.
     *
     * @param string $name
     * @param array<mixed> $args
     * @return mixed
     */
    public function __call(string $name, array $args): mixed
    {
        return $this->newQuery()->{$name}(...$args);
    }

    /**
     * Handle static calls.
     *
     * @param string $name
     * @param array<mixed> $args
     * @return mixed
     */
    public static function __callStatic(string $name, array $args): mixed
    {
        return (new static())->{$name}(...$args);
    }
}
