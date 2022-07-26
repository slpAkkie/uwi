<?php

namespace Uwi\Contracts\Support\Facades;

interface FacadeContract
{
    /**
     * Return a corresponding class or a Contract from Container bindings.
     *
     * @return string
     */
    public function getAccessor(): string;
}
