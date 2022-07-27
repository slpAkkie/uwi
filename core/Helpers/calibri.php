<?php

use Uwi\Contracts\Http\Response\ResponseContract;
use Uwi\Services\Calibri\Contracts\ViewContract;

/**
 * Returns view as a response.
 *
 * @param string $view
 * @param array $params
 * @return ResponseContract
 */
function view(string $view, array $params = []): ResponseContract
{
    $view = app()->make(ViewContract::class, $view, $params);

    return response($view);
}
