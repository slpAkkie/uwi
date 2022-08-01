<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;

class IfDirective implements DirectiveContract
{
    /**
     * End directive string.
     * 
     * @var string
     */
    protected const END_DIRECTIVE = '#endif';

    /**
     * Default content when condition is false.
     * 
     * @var string
     */
    protected const DEFAULT_CONTENT = '';

    /**
     * True or false that is an indicator to output content.
     *
     * @var boolean
     */
    protected bool $condition;

    /**
     * Instantiate Directive.
     *
     * @param CompilerContract $compiler
     * @param string $condition
     * @param mixed $inlineContent
     */
    public function __construct(
        protected CompilerContract $compiler,
        string $condition,
        protected mixed $inlineContent = null,
    ) {
        $this->condition = reval($condition, $this->compiler->getParams());

        if ($this->inlineContent) {
            $this->inlineContent = reval($this->inlineContent, $this->compiler->getParams());
        }
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        $content = !is_null($this->inlineContent)
            ? $this->inlineContent
            : $this->compiler->readUntil(self::END_DIRECTIVE);

        return $this->condition ? $content : self::DEFAULT_CONTENT;
    }
}
