<?php

/**
 * Helper to create response.
 *
 * @param mixed $data
 * @param integer $responseCode
 * @return \Uwi\Contracts\Http\Response\ResponseContract
 */
function response(mixed $data = null, int $responseCode = 200): \Uwi\Contracts\Http\Response\ResponseContract
{
    return \Uwi\Services\Http\Response\Facades\Response::make($data, $responseCode);
}
