<?php

namespace Uwi\Services\Calibri\Contracts;

use Uwi\Contracts\Container\SingletonContract;

interface CompilerContract extends SingletonContract
{
    /**
     * Instantiate new Compiler instance.
     *
     * @param string $viewPath
     * @param array $params
     */
    public function __construct(string $viewPath, array $params = []);

    /**
     * Compile view content.
     *
     * @return string
     */
    public function compile(): string;
}
