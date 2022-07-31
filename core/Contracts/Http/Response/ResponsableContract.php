<?php

namespace Uwi\Contracts\Http\Response;

use Uwi\Contracts\Http\Request\RequestContract;

interface ResponsableContract
{
    /**
     * Convert object to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     * @return mixed
     */
    public function toResponse(RequestContract $request): mixed;
}
