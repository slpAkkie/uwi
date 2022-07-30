<?php

/**
 * Returns view as a response.
 *
 * @param string $view
 * @param array<string, mixed> $params
 * @return \Uwi\Contracts\Http\Response\ResponseContract
 */
function view(string $view, array $params = []): \Uwi\Contracts\Http\Response\ResponseContract
{
    $view = app()->make(\Uwi\Services\Calibri\Contracts\ViewContract::class, $view, $params);

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
