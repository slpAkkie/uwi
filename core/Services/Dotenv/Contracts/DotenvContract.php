<?php

namespace Uwi\Services\Dotenv\Contracts;

use Uwi\Contracts\Container\SingletonContract;

interface DotenvContract extends SingletonContract
{
    /**
     * Set many values.
     *
     * @param array<string, string> $vars
     * @return \Uwi\Services\Dotenv\Contracts\DotenvContract
     */
    public function setMany(array $vars = []): \Uwi\Services\Dotenv\Contracts\DotenvContract;

    /**
     * Set single value.
     *
     * @param string|int $key
     * @param string $val
     * @return string
     */
    public function set(string $key, string $val): string;

    /**
     * Get single value.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function get(string $key, string|null $default = null): string|null;

    /**
     * Unset the value.
     *
     * @param string $key
     * @return string
     */
    public function unset(string $key): string;

    /**
     * Check wheter the key exists.
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool;

    /**
     * Load vars from env file.
     *
     * @return void
     */
    public function loadEnvars(string $envFile): void;
}
