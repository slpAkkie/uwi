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

/**
 * Work like regular trim but removes
 * only one character from the start and the end.
 *
 * @param string $string
 * @param string $characters
 * @return string
 */
function trim_once(string $string, string $characters): string
{
    $characters = preg_quote($characters);
    return preg_replace("#^([$characters])?(.*?)([$characters])?$#", '$2', $string);
}
