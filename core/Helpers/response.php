<?php

use Uwi\Contracts\Http\Response\ResponseContract;
use Uwi\Services\Http\Response\Facades\Response;

/**
 * Helper to create response.
 *
 * @param mixed $data
 * @param integer $responseCode
 * @return ResponseContract
 */
function response(mixed $data = null, int $responseCode = 200): ResponseContract
{
    return Response::make($data, $responseCode);
}
