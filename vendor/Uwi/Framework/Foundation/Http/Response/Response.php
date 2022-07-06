<?php

namespace Uwi\Foundation\Http\Response;

use Uwi\Contracts\Http\Response\ResponseContract;

class Response implements ResponseContract
{
    /**
     * Response status code
     *
     * @var integer
     */
    public int $statusCode = 200;

    /**
     * Response data
     *
     * @var mixed
     */
    public mixed $data = null;

    /**
     * Set status code for the response
     *
     * @param integer $statusCode
     * @return static
     */
    public function setStatusCode(int $statusCode): static
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Set response data
     *
     * @param mixed $data
     * @return static
     */
    public function setData(mixed $data = null): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set headers for the response
     *
     * @return static
     */
    public function setHeaders(): static
    {
        http_response_code($this->statusCode);

        return $this;
    }

    /**
     * Send the response
     *
     * @return void
     */
    public function send(): void
    {
        $this->setHeaders();

        echo $this->data;
    }
}
