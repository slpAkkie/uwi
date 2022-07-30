<?php

namespace Uwi\Services\Http\Response\Factory;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Response\ResponseContract;

class ResponseFactory
{
    /**
     * Instantiate ResponseFactory.
     *
     * @param ApplicationContract $app
     */
    public function __construct(
        protected ApplicationContract $app,
    ) {
        //
    }

    /**
     * Make Response from provided value.
     *
     * @param mixed $response
     * @param int $responseCode
     * @return ResponseContract
     */
    public function make(mixed $response, int $responseCode = 200): ResponseContract
    {
        if (is_subclass_of($response, ResponseContract::class)) {
            return $response;
        }

        return $this->app->make(ResponseContract::class, $response, $responseCode);
    }
}
