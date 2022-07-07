<?php

namespace Uwi\Dotenv;

use Uwi\Contracts\SingletonContract;
use Uwi\Filesystem\Filesystem;
use Uwi\Support\Arrays\ArrayWrapper;

class Dotenv extends ArrayWrapper implements SingletonContract
{
    /**
     * Env file name
     * 
     * @var string
     */
    private const ENV_FILENAME = '.env';

    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this->store = &$_ENV;
        $this->loadEnv();
    }

    /**
     * Load vars from env file
     *
     * @return void
     */
    public function loadEnv(): void
    {
        $envars = array_filter(explode("\n", Filesystem::getFileContent(APP_BASE_PATH, static::ENV_FILENAME)));
        foreach ($envars as $envar) {
            $exploded = explode('=', $envar);
            $key = $exploded[0];
            $val = join('=', array_slice($exploded, 1));

            $this->set($key, $val);
        }
    }
}
