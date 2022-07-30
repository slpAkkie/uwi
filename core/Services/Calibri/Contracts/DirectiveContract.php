<?php

namespace Uwi\Services\Calibri\Contracts;

interface DirectiveContract
{
    /**
     * Returns compiled directive content.
     *
     * @return string
     */
    public function compile(): string;
}
