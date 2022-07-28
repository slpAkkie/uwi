<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class SectionDirective implements DirectiveContract
{
    public function __construct(
        protected CompilerContract $compiler,
        protected string $name,
        protected mixed $inlineContent = null,
    ) {
        $this->name = trim($this->name, '\'"');
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        if (!is_null($this->inlineContent)) {
            $this->compiler->share("section.{$this->name}", $this->inlineContent);
        } else {
            $this->compiler->share("section.{$this->name}", '');
        }

        return '';
    }
}
