<?php

namespace Uwi\Foundation\Exceptions;

use Uwi\Contracts\Application\Exceptions\ExceptionContract;
use Uwi\Contracts\Http\Request\RequestContract;

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
        return view('errors.500', [
            'e' => $this,
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
        return view('errors.500', [
            'e' => $e,
        ])->statusCode(self::DEFAULT_STATUS_CODE);
    }
}
