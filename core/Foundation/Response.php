<?php

namespace Uwi\Foundation;

use Uwi\Contracts\Application\ResponseContract;

abstract class Response implements ResponseContract
{
    /**
     * Instantiate new Response.
     *
     * @param mixed $data
     */
    public function __construct(
        protected mixed $data,
    ) {
        //
    }
}
