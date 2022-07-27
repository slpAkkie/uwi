<?php

namespace Uwi\Contracts\Http\Response;

use Uwi\Contracts\Http\Request\RequestContract;

interface ResponsableContract
{
    /**
     * Convert object to response.
     *
     * @param RequestContract $request
     * @return mixed
     */
    public function toResponse(RequestContract $request): mixed;
}
