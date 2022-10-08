<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Foundation\Exceptions\Exception;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class SectionDirective implements DirectiveContract
{
    /**
     * End directive string.
     *
     * @var string
     */
    protected const END_DIRECTIVE = '#endsection';

    /**
     * Instantiate Directive.
     *
     * @param CompilerContract $compiler
     * @param string $name
     * @param mixed $inlineContent
     */
    public function __construct(
        protected CompilerContract $compiler,
        protected string $name,
        protected mixed $inlineContent = null,
    ) {
        $this->name = trimQuotes($this->name);

        if ($this->inlineContent) {
            $this->inlineContent = reval($this->inlineContent, $this->compiler->getParams());

            if (is_null($this->inlineContent)) {
                throw new Exception("[Section: {$this->name}] If inline content in the section specified it's cannot be null");
            }
        }
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
            $content = $this->compiler->readUntil(self::END_DIRECTIVE);

            $this->compiler->share("section.{$this->name}", $content);
        }

        return '';
    }
}
