<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class ErrorDirective implements DirectiveContract
{
    /**
     * End directive string.
     * 
     * @var string
     */
    protected const END_DIRECTIVE = '#enderror';

    /**
     * Instantiate new Directive instance.
     *
     * @param CompilerContract $compiler
     * @param string $errorKey
     */
    public function __construct(
        protected CompilerContract $compiler,
        protected string $errorKey,
    ) {
        $this->errorKey = trim($this->errorKey, '\'"');
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        $content = $this->compiler->readUntil(self::END_DIRECTIVE);

        return "{ Error if {$this->errorKey} }";
    }
}
