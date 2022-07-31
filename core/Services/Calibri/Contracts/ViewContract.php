<?php

namespace Uwi\Services\Calibri\Contracts;

use Uwi\Contracts\Http\Response\ResponsableContract;

interface ViewContract extends ResponsableContract
{
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
    public function toResponse(\Uwi\Contracts\Http\Request\RequestContract $request): mixed;
}
