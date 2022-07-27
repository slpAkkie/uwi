<?php

namespace Uwi\Services\Http\Response;

use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Response\ResponsableContract;
use Uwi\Contracts\Http\Response\ResponseContract;

class Response implements ResponseContract
{
    /**
     * Default http response code.
     */
    protected const DEFAULT_RESPONSE_CODE = 200;

    /**
     * Array of headers that should be set for the response.
     *
     * @var array<string, string>
     */
    protected array $headers = [];

    /**
     * Instantiate new Response.
     *
     * @param mixed $data
     */
    public function __construct(
        protected RequestContract $request,
        protected mixed $data,
        protected int $responseCode = self::DEFAULT_RESPONSE_CODE,
    ) {
        //
    }

    /**
     * Get or set response status code.
     *
     * @param integer|null $statusCode
     * @return static
     */
    public function statusCode(int|null $statusCode = null): static
    {
        if (!is_null($statusCode)) {
            $this->responseCode = $statusCode;
        }

        return $this;
    }

    /**
     * Add header to headers array.
     *
     * @param string $header
     * @param string $val
     * @return void
     */
    protected function pushHeader(string $header, string $val): void
    {
        $this->headers[$header] = $val;
    }

    /**
     * Set response headers.
     *
     * @return void
     */
    protected function setHeaders(): void
    {
        http_response_code($this->responseCode);
        $this->statusCode();

        foreach ($this->headers as $header => $val) {
            $this->setHeader($header, $val);
        }
    }

    /**
     * Set response header.
     *
     * @return void
     */
    protected function setHeader(string $header, string $val): void
    {
        header("$header: $val");
    }

    /**
     * Set response header to send a JSON.
     *
     * @param mixed $data
     * @param int $responseCode
     * @return static
     */
    public function json(mixed $data = null, int $responseCode = null): static
    {
        $this->data = json_encode($data ?? $this->data ?? []);
        $this->pushHeader('Content-Type', 'application/json');
        $this->statusCode($responseCode);

        return $this;
    }

    /**
     * Set response header to send a HTML.
     *
     * @param mixed $data
     * @param int $responseCode
     * @return static
     */
    public function html(mixed $data = null, int $responseCode = null): static
    {
        $this->data = $data ?? $this->data;
        $this->pushHeader('Content-Type', 'text/html');
        $this->statusCode($responseCode ?? self::DEFAULT_RESPONSE_CODE);

        return $this;
    }

    protected function getResponseData(): mixed
    {
        return is_subclass_of($this->data, ResponsableContract::class)
            ? $this->data->toResponse($this->request)
            : $this->data;
    }

    /**
     * Send response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        $this->setHeaders();
        $response = $this->getResponseData();

        if (is_string($response)) {
            $this->html($response);
        } else {
            $this->json($response);
        }

        $this->print($response);
    }

    /**
     * Print response content on a page.
     *
     * @param string $response
     * @return void
     */
    protected function print(string $response): void
    {
        echo $response;
    }
}
