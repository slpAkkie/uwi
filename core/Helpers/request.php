<?php

/**
 * Returns base RequestContract instance for the Kernel.
 *
 * @return \Uwi\Contracts\Http\Request\RequestContract
 */
function request(): \Uwi\Contracts\Http\Request\RequestContract
{
    return app()->resolve(\Uwi\Contracts\Http\Request\RequestContract::class);
}
