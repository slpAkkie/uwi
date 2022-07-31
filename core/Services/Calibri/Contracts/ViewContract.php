<?php

namespace Uwi\Services\Calibri\Contracts;

use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Response\ResponsableContract;

interface ViewContract extends ResponsableContract
{
    /**
     * Add new namespace for views.
     *
     * @param string $namespace
     * @param string $path
     * @return void
     */
    public static function namespace(string $namespace, string $path): void;

    /**
     * Returns true or false depending on
     * whether the provided view exists.
     *
     * @param string $view
     * @return boolean
     */
    public static function exists(string $view): bool;

    /**
     * Render view.
     *
     * @return string
     */
    public function render(): string;

    /**
     * Convert object to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     * @return mixed
     */
    public function toResponse(RequestContract $request): mixed;
}
