<?php

namespace Uwi\Contracts\Http\Response;

interface ResponseContract
{
    /**
     * Set status code for the response
     *
     * @param integer $statusCode
     * @return static
     */
    public function setStatusCode(int $statusCode): static;

    /**
     * Set response data
     *
     * @param mixed $data
     * @return static
     */
    public function setData(mixed $data = null): static;

    /**
     * Send the response
     *
     * @return void
     */
    public function send(): void;
}
