<?php

namespace Uwi\Contracts\Application\Exceptions;

use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Response\ResponsableContract;

interface ExceptionContract extends ResponsableContract
{
    /**
     * Convert exception to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     * @return mixed
     */
    public function toResponse(RequestContract $request): mixed;

    /**
     * Make response from some other kind of Exceptions.
     *
     * @param \Throwable $e
     * @return mixed
     */
    public static function makeResponse(\Throwable $e): mixed;
}
