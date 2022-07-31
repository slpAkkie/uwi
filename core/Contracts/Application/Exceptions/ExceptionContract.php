<?php

namespace Uwi\Contracts\Application\Exceptions;

use Uwi\Contracts\Http\Response\ResponsableContract;

interface ExceptionContract extends ResponsableContract
{
    /**
     * Make response from some other kind of Exceptions.
     *
     * @param \Throwable $e
     * @return mixed
     */
    public static function makeResponse(\Throwable $e): mixed;
}
