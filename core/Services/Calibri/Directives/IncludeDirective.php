<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;
use Uwi\Services\Calibri\Contracts\ViewContract;

class IncludeDirective implements DirectiveContract
{
    /**
     * Instantiate Directive.
     *
     * @param ApplicationContract $app
     * @param CompilerContract $compiler
     * @param string $view
     */
    public function __construct(
        protected ApplicationContract $app,
        protected CompilerContract $compiler,
        protected string $view,
    ) {
        $this->view = trimQuotes($this->view);
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        $this->compiler->setView($this->app->make(ViewContract::class, $this->view));

        return '';
    }
}
