<?php

namespace Uwi\Services\Http\Response\Factory;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\ResponseContract;

class ResponseFactory
{
    /**
     * Instantiate Response Factory.
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
