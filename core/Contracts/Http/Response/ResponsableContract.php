<?php

namespace Uwi\Contracts\Http\Response;

interface ResponsableContract
{
    /**
     * Convert object to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     * @return mixed
     */
    public function toResponse(\Uwi\Contracts\Http\Request\RequestContract $request): mixed;
}
