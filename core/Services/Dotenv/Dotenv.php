<?php

namespace Uwi\Services\Dotenv;

use Uwi\Contracts\DotenvContract;

class Dotenv implements DotenvContract
{
    /**
     * Default name of env file.
     * 
     * @var string
     */
    private const ENV_FILENAME = '.env';

    /**
     * Array of env vars.
     *
     * @var array<string, string>
     */
    protected array $envars;

    /**
     * Instantiate Dotenv Service.
     */
    public function __construct()
    {
        $this->envars = &$_ENV;
    }

    /**
     * Set many values.
     *
     * @param array<string, string> $vars
     * @return static
     */
    public function setMany(array $vars = []): static
    {
        $this->envars = array_merge($this->envars, $vars);

        return $this;
    }

    /**
     * Set single value.
     *
     * @param string|int $key
     * @param string $val
     * @return string
     */
    public function set(string $key, string $val): string
    {
        return $this->envars[$key] = $val;
    }

    /**
     * Get single value.
     *
     * @param string $key
     * @param string|null $default
     * @return string|null
     */
    public function get(string $key, string|null $default = null): string|null
    {
        return $this->has($key) ? $this->envars[$key] : $default;
    }

    /**
     * Unset the value.
     *
     * @param string $key
     * @return string
     */
    public function unset(string $key): string
    {
        $val = $this->envars[$key];
        unset($this->envars[$key]);

        return $val;
    }

    /**
     * Check wheter the key exists.
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool
    {
        return key_exists($key, $this->envars);
    }

    /**
     * Load vars from env file.
     *
     * @return void
     */
    public function loadEnvars(string $envFile = self::ENV_FILENAME): void
    {
        $envars = array_filter(explode("\n", file_get_contents(APP_BASE_PATH . DIRECTORY_SEPARATOR . $envFile)));

        foreach ($envars as $envar) {
            $envar = explode('=', $envar);
            $key = $envar[0];
            $val = join('=', array_slice($envar, 1));

            $this->set($key, $val);
        }
    }
}
