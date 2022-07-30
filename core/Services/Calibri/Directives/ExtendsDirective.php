<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class ExtendsDirective implements DirectiveContract
{
    public function __construct(
        protected CompilerContract $compiler,
        protected string $template,
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
        $this->compiler->read();

        return '';
    }
}
