<?php

namespace Uwi\Contracts\Http\Response;

interface ResponseContract
{
    /**
     * Get or set response status code.
     *
     * @param integer|null $statusCode
     * @return \Uwi\Contracts\Http\Response\ResponseContract
     */
    public function statusCode(int|null $statusCode = null): \Uwi\Contracts\Http\Response\ResponseContract;

    /**
     * Set response header to send a JSON.
     *
     * @param mixed $data
     * @param int $responseCode
     * @return \Uwi\Contracts\Http\Response\ResponseContract
     */
    public function json(mixed $data = null, int $responseCode = null): \Uwi\Contracts\Http\Response\ResponseContract;


    /**
     * Set response header to send a HTML.
     *
     * @param mixed $data
     * @param int $responseCode
     * @return \Uwi\Contracts\Http\Response\ResponseContract
     */
    public function html(mixed $data = null, int $responseCode = null): \Uwi\Contracts\Http\Response\ResponseContract;

    /**
     * Send response to the client.
     *
     * @return void
     */
    public function send(): void;
}
