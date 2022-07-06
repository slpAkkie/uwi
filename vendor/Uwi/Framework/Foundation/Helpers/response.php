<?php

use Uwi\Calibri\View;
use Uwi\Contracts\Http\Response\ResponseContract;

/**
 * Get Response instance
 *
 * @param integer $code
 * @return ResponseContract
 */
function response(mixed $data = null, int $code = 200): ResponseContract
{
    $response = app()->instantiate(ResponseContract::class);

    $response
        ->setData($data)
        ->setStatusCode($code);

    return $response;
}

function view(string $viewName, array $args = []): ResponseContract
{
    $response = app()->instantiate(ResponseContract::class);

    $response
        ->setData(app()->instantiate(View::class, $viewName, $args));

    return $response;
}
