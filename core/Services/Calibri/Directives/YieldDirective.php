<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class YieldDirective implements DirectiveContract
{
    /**
     * Instantiate Directive.
     *
     * @param CompilerContract $compiler
     * @param string $section
     * @param string $default
     */
    public function __construct(
        protected CompilerContract $compiler,
        protected string $section,
        protected string $default = '',
    ) {
        $this->section = trimQuotes($this->section);

        if ($this->default) {
            $this->default = reval($this->default, $this->compiler->getParams());
        }
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        $sectionContent = $this->compiler->get("section.{$this->section}");

        return trimQuotes($sectionContent ? $sectionContent : $this->default);
    }
}
