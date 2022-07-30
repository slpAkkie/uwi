<?php

namespace Uwi\Services\Calibri\Contracts;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Container\SingletonContract;

interface CompilerContract extends SingletonContract
{
    /**
     * Instantiate Compiler.
     *
     * @param ApplicationContract $app
     */
    public function __construct(
        ApplicationContract $app,
    );

    /**
     * Set view file to read from.
     *
     * @param string $viewPath
     * @param array<string, mixed> $params
     * @return \Uwi\Services\Calibri\Contracts\CompilerContract
     */
    public function setView(string $viewPath, array $params = []): \Uwi\Services\Calibri\Contracts\CompilerContract;

    /**
     * Read next until provided string isn't found.
     *
     * @param string $needle
     * @return string
     */
    public function readUntil(string $needle): string;

    /**
     * Read all view file and return compiled content.
     *
     * @return string
     */
    public function read(): string;

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
     * Resturns shared data by provided key.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;
}
