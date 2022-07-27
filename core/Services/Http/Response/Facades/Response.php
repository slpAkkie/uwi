<?php

namespace Uwi\Services\Http\Response\Facades;

use Uwi\Services\Http\Response\Factory\ResponseFactory;
use Uwi\Support\Facades\Facade;

class Response extends Facade
{
    /**
     * Return a corresponding class or a Contract from Container bindings.
     *
     * @return string
     */
    public function getAccessor(): string
    {
        return ResponseFactory::class;
    }
}
