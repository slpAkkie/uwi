<?php

namespace Uwi\Services\Calibri\Contracts;

interface CompilerContract
{
    /**
     * Set view file to read from.
     *
     * @param bool $last - Indicate that view should be compiled last of all.
     * @param \Uwi\Services\Calibri\Contracts\ViewContract $view
     * @return \Uwi\Services\Calibri\Contracts\CompilerContract
     */
    public function setView(ViewContract $view, bool $last = false): \Uwi\Services\Calibri\Contracts\CompilerContract;

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

    /**
     * Returns array of parameters.
     *
     * @return array
     */
    public function getParams(): array;

    /**
     * Read next until provided string isn't found.
     *
     * @param string $needle
     * @return string
     */
    public function readUntil(string $needle): string;

    /**
     * Compile view content.
     *
     * @return string
     */
    public function compile(): string;
}
