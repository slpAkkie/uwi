<?php

namespace Uwi\Services\Calibri\Contracts;

interface DirectiveContract
{
    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string;
}
