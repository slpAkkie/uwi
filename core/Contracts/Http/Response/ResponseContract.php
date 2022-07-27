<?php

namespace Uwi\Contracts\Application;

interface ResponseContract
{
    /**
     * Get or set response status code.
     *
     * @param integer|null $statusCode
     * @return integer
     */
    public function statusCode(int|null $statusCode = null): int;

    /**
     * Set response header to send a JSON.
     *
     * @param mixed $data
     * @param int $responseCode
     * @return static
     */
    public function json(mixed $data = null, int $responseCode = null): static;


    /**
     * Set response header to send a HTML.
     *
     * @param mixed $data
     * @param int $responseCode
     * @return static
     */
    public function html(mixed $data = null, int $responseCode = null): static;

    /**
     * Send response to the client.
     *
     * @return void
     */
    public function send(): void;
}
