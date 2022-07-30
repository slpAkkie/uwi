<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;
use Uwi\Services\Calibri\Contracts\ViewContract;

class ExtendsDirective implements DirectiveContract
{
    /**
     * Instantiate Directive.
     *
     * @param ApplicationContract $app
     * @param CompilerContract $compiler
     * @param string $template
     */
    public function __construct(
        protected ApplicationContract $app,
        protected CompilerContract $compiler,
        protected string $template,
    ) {
        $this->template = trim($this->template, '\'"');
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        // Compile view content with sections for the template.
        $this->compiler->read();

        // Create new view from specified template and render it.
        return $this->app->make(ViewContract::class, $this->template)->render();
    }
}
