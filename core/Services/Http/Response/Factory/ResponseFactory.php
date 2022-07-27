<?php

namespace Uwi\Services\Http\Response\Factory;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Application\ResponseContract;
use Uwi\Services\Http\Response\HttpResponse;

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
     * @return ResponseContract
     */
    public function make(mixed $response): ResponseContract
    {
        if (is_subclass_of($response, ResponseContract::class)) {
            return $response;
        }

        return $this->app->make(HttpResponse::class, $response);
    }
}
