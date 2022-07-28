<?php

namespace Uwi\Services\Calibri\Contracts;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Container\SingletonContract;

interface CompilerContract extends SingletonContract
{
    /**
     * Instantiate new Compiler instance.
     *
     * @param ApplicationContract $app
     * @param string $viewPath
     * @param array $params
     */
    public function __construct(
        ApplicationContract $app,
        string $viewPath,
        array $params = []
    );

    /**
     * Compile view content.
     *
     * @return string
     */
    public function compile(): string;

    /**
     * Share something into Compiler.
     *
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function share(string $key, mixed $val): void;

    /**
     * Resturns shared data.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;
}
