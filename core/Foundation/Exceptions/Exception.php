<?php

namespace Uwi\Foundation\Exceptions;

use Uwi\Contracts\Application\Exceptions\ExceptionContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Services\Calibri\Contracts\ViewContract;

class Exception extends \Exception implements ExceptionContract
{
    protected const DEFAULT_STATUS_CODE = 500;

    /**
     * Instantiate new Exception.
     *
     * @param string $message
     * @param integer $statusCode
     */
    public function __construct(
        string $message = '',
        protected int $statusCode = self::DEFAULT_STATUS_CODE,
    ) {
        $this->message = $message;
    }

    /**
     * Convert object to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     * @return mixed
     */
    public function toResponse(RequestContract $request): mixed
    {
        // Assume a view for the exception.
        // If no such view then default exception view will be set.
        $view = 'errors::' . $this->statusCode;
        if (!app()->tapStatic([ViewContract::class, 'exists'], $view)) {
            $view = 'errors::' . self::DEFAULT_STATUS_CODE;
        }

        return view($view, [
            'e' => $this,
            'responseCode' => $this->statusCode
        ])->statusCode($this->statusCode);
    }

    /**
     * Make response from some other kind of Exceptions.
     *
     * @param \Throwable $e
     * @return mixed
     */
    public static function makeResponse(\Throwable $e): mixed
    {
        return view('errors::500', [
            'e' => $e,
            'responseCode' => self::DEFAULT_STATUS_CODE
        ])->statusCode(self::DEFAULT_STATUS_CODE);
    }
}
