<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\DirectiveContract;

class YieldDirective implements DirectiveContract
{
    public function __construct(
        protected string $section,
    ) {
        //
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        return '';
    }
}
