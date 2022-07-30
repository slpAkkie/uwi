<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class YieldDirective implements DirectiveContract
{
    public function __construct(
        protected CompilerContract $compiler,
        protected string $section,
    ) {
        $this->section = trim($this->section, '\'"');
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        $sectionContent = $this->compiler->get("section.{$this->section}");
        return $sectionContent ? $sectionContent : '';
    }
}
